<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\AttendanceDevice;
use App\Models\RfidDevice;
use App\Models\StudentAttendance;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\AttendanceTimeSetting;
use App\Models\WhatsappSetting;
use App\Models\Student;
use App\Models\RfidMaster;
use App\Http\Resources\AttendanceResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;



class AttendanceController extends Controller
{
    public function index()
    {
        //get all products
        $attendances = StudentAttendance::latest()->paginate(5);

        // dd($attendances);

        //return collection of attendances as a resource
        return new AttendanceResource(true, 'List data presensi', $attendances);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rfid_uid' => 'required|string',
            'device_number' => 'required|string',
        ]);

        if ($validator->fails()) {
            return new AttendanceResource(false, 'Validation error', $validator->errors());
        }

        $rfid_uid = $request->rfid_uid;
        $deviceNumber = $request->device_number;

        // ============================================
        // 2. Cari student berdasarkan RFID
        // ============================================
        $attendanceDevice = AttendanceDevice::where('serial_number', $deviceNumber)->first();
        if (!$attendanceDevice) {
            return new AttendanceResource(false, 'Belum terdaftar', [
                'device_number' => $request->device_number,
            ]);
        }


        // ============================================
        // 3. Jika RFID belum terdaftar sebagai student
        //    simpan ke RfidMaster
        // ============================================
        $rfidMaster = RfidMaster::where('rfid_uid', $rfid_uid)->first();
        if (!$rfidMaster) {
            RfidMaster::updateOrCreate(
                ['rfid_uid' => $rfid_uid],
                [
                    'device' => $attendanceDevice->name,
                    'is_active' => false,
                ]
            );

            return new AttendanceResource(false, 'Belum terdaftar', [
                'rfid_uid' => $rfid_uid,
            ]);
        }

        $student = Student::where('id', $rfidMaster->student_id)->first();

        // ============================================
        // 4. Ambil aturan presensi
        // ============================================
        $rule = Cache::rememberForever('attendance_time_setting', function () {
            return AttendanceTimeSetting::first();
        });

        $now = now();
        $time = $now->format('H:i:s');
        $today = $now->toDateString();

        $attendance = StudentAttendance::where('student_id', $student->id)
            ->whereDate('date', $today)
            ->first();

        // ============================================
        // 5. Terlalu pagi
        // ============================================
        if ($time < $rule->in_start) {
            return new AttendanceResource(false, 'Gagal presensi', null);
        }

        // ============================================
        // 6. Absen Masuk
        // ============================================
        
        if ($time >= $rule->in_start && $time < $rule->out_start) {
            $status = ($time >= $rule->in_end && $time < $rule->out_start)
                ? 'terlambat'
                : 'hadir';

            if ($attendance && $attendance->check_in !== null) {
                return new AttendanceResource(false, 'Sudah presensi!', null);
            }

            $classId = $student->classes()
                ->wherePivot('academic_year_id', $this->academicYearActive())
                ->value('classes.id');

            StudentAttendance::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'date' => $today
                ],
                [
                    'student_id' => $student->id,
                    'academic_year_id' => $this->academicYearActive(),
                    'class_id' => $classId,
                    'date' => $today,
                    'check_in' => $time,
                    'status' => $status,
                    'device' => $attendanceDevice->name,
                ]
            );

            $this->sendWhatsappNotification($student, $time, 'masuk');

            return new AttendanceResource(true, $student->name, [
                'type' => 'in',
                'time' => $time,
                'late' => $status === 'terlambat',
            ]);
        }

        // ============================================
        // 7. Absen Pulang
        // ============================================
        if ($time >= $rule->out_start && $time <= $rule->out_end) {

            if (!$attendance) {
                return new AttendanceResource(false, 'Belum absensi!', null);
            }

            if ($attendance->check_out !== null) {
                return new AttendanceResource(false, 'Sudah absensi!', null);
            }

            $attendance->update([
                'check_out' => $time,
                'device' => $attendanceDevice->name,
            ]);

            $this->sendWhatsappNotification($student, $time, 'pulang');

            return new AttendanceResource(true, $student->name, [
                'type' => 'out',
                'time' => $time,
            ]);
        }

        return new AttendanceResource(false, 'Bukan waktu absensi.', null);
    }

    /**
     * =========================================================
     * ✅ FUNGSI KIRIM WA (DALAM SATU KONTROLLER)
     * =========================================================
     */
    // private function sendWhatsappNotification($student, $time, $type)
    // {
    //     $messageType = $type === 'masuk' ? 'ABSEN MASUK' : 'ABSEN PULANG';

    //     foreach ($student->guardians()->whereNotNull('phone')->get() as $guardian) {

    //         $message = "Halo, *{$guardian->name}*\n\n"
    //             . "Ananda *{$student->name}* telah *{$messageType}*.\n\n"
    //             . "*Tanggal:* " . now()->format('d-m-Y') . "\n"
    //             . "*Jam:* {$time}\n\n"
    //             . "Terima kasih.";

    //         SendWhatsappNotificationJob::dispatch($guardian->phone, $message);
    //     }
    // }

    private function sendWhatsappNotification($student, $time, $type)
    {
        $setting = WhatsappSetting::first();
        if (!$setting)
            return;

        $messageType = $type === 'masuk' ? 'ABSEN MASUK' : 'ABSEN PULANG';

        foreach ($student->guardians()
            ->where('is_notif', true)
            ->whereHas('user', fn($q) => $q->whereNotNull('phone'))
            ->get() as $guardian) {

            $phone = $guardian->user->phone; // ambil dari user

            $message = "Halo, *{$guardian->user->name}*\n\n"
                . "Ananda *{$student->name}* telah *{$messageType}*.\n\n"
                . "*Tanggal:* " . now()->format('d-m-Y') . "\n"
                . "*Jam:* {$time}\n\n"
                . "Terima kasih.";

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $setting->api_url . 'send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => [
                    'target' => '62' . $phone,
                    'message' => $message,
                ],
                CURLOPT_HTTPHEADER => [
                    'Authorization: ' . $setting->token
                ],
            ]);

            curl_exec($curl);
            curl_close($curl);
        }
    }

    public function notification()
    {
        $student = Student::where('rfid', 'CE24BA03')->first();

        $this->sendWhatsappNotification($student, '17.00', 'pulang');

        //return collection of attendances as a resource
        return new AttendanceResource(true, 'List Data Attendance', $student);
    }

    private function academicYearActive()
    {
        return AcademicYear::where('is_active', true)
            ->value('id');
    }
}
