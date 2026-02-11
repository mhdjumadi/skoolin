<div id="qr-reader" style="width: 100%; height: 400px;"></div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    document.addEventListener('livewire:load', function () {
        // tunggu 200ms supaya modal render dulu
        setTimeout(() => {
            const html5QrCode = new Html5Qrcode("qr-reader");

            html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10 },
                qrCodeMessage => {
                    window.location.href = `/journal/scan?schedule_id=${qrCodeMessage}`;
                }
            );
        }, 200);
    });
</script>