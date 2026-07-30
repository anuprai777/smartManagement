@extends('layouts.app')

@section('title', 'Scan Tickets')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Scan Tickets</h1>
        <p class="text-gray-500 mt-1">Scan a QR code, upload a QR image, or enter a ticket number to verify attendance.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Column -->
        <div class="space-y-6">
            <!-- Live Camera Scan -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                    Live Camera Scan
                </h2>

                <!-- Camera Status -->
                <div id="camera-status" class="hidden mb-3">
                    <div class="flex items-center gap-2 text-sm">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse" id="camera-dot"></span>
                        <span id="camera-status-text" class="text-gray-500">Initializing camera...</span>
                    </div>
                </div>

                <!-- Camera Selector -->
                <div id="camera-select-section" class="hidden mb-4">
                    <div class="flex gap-2">
                        <select id="camera-select"
                            class="flex-1 px-3 py-2 border border-gray-200 rounded-xl text-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200/50 bg-white">
                        </select>
                        <button onclick="switchCamera()"
                            class="px-3 py-2 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition">
                            Switch
                        </button>
                    </div>
                </div>

                <!-- Camera Error -->
                <div id="camera-error" class="hidden text-center py-4">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-red-600 font-medium mb-1">Camera Unavailable</p>
                    <div id="camera-error-message" class="text-sm text-gray-500 mb-3"></div>
                    <button onclick="initCamera()" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition">
                        Retry Camera Access
                    </button>
                    <p class="text-xs text-gray-400 mt-2">Or use <strong>Upload QR Image</strong> or <strong>Manual Entry</strong> below.</p>
                </div>

                <div id="qr-reader" class="w-full max-w-sm mx-auto"></div>
                <div id="qr-reader-results" class="mt-3 text-center text-sm text-gray-500"></div>

                <!-- Camera toggle -->
                <div class="mt-4 flex justify-center">
                    <button id="toggle-camera-btn" onclick="toggleCamera()"
                        class="px-4 py-2 text-sm font-medium rounded-xl transition hidden
                            bg-red-100 text-red-700 hover:bg-red-200">
                        <svg class="w-4 h-4 inline mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        Stop Camera
                    </button>
                </div>
            </div>

            <!-- Upload QR Image -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Upload QR Image
                </h2>
                <p class="text-xs text-gray-400 mb-4">Take a photo of the ticket QR code or upload a screenshot.</p>
                <div id="upload-area"
                    class="relative border-2 border-dashed border-gray-200 rounded-xl p-8 text-center hover:border-emerald-300 hover:bg-emerald-50/30 transition-all cursor-pointer">
                    <input type="file" id="qr-image-input" accept="image/*" capture="environment"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    <svg class="w-10 h-10 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z"/>
                    </svg>
                    <p class="text-sm text-gray-500">
                        <span class="font-medium text-emerald-600">Click to choose</span> or take a photo
                    </p>
                    <p id="upload-file-name" class="text-xs text-gray-400 mt-1">PNG, JPG, WebP</p>
                </div>
                <div id="upload-progress" class="hidden mt-3 text-center">
                    <svg class="animate-spin w-6 h-6 mx-auto text-emerald-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <p class="text-xs text-gray-500 mt-1">Scanning QR code from image...</p>
                </div>
            </div>

            <!-- Manual Entry -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                    Manual Ticket Entry
                </h2>
                <form id="manual-verify-form" class="flex gap-2">
                    @csrf
                    <input type="text" id="manual-ticket" placeholder="Enter ticket number..."
                        class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl focus:border-indigo-300 focus:ring focus:ring-indigo-200/50 text-sm">
                    <button type="submit" class="px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition">
                        Verify
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Column: Result -->
        <div class="space-y-4">
            <h2 class="text-lg font-semibold text-gray-900">Verification Result</h2>
            <div id="scan-result" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 min-h-[300px]">
                <div class="text-center py-8 text-gray-400">
                    <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                    <p>Scan a QR code with your camera, upload an image, or enter a ticket number.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let html5QrCode = null;
let isScanning = false;

// ─── QR Scanner ───────────────────────────────────────────────────
async function initCamera() {
    const status = document.getElementById('camera-status');
    const errorDiv = document.getElementById('camera-error');
    const selectSection = document.getElementById('camera-select-section');
    const select = document.getElementById('camera-select');
    const toggleBtn = document.getElementById('toggle-camera-btn');
    const resultsEl = document.getElementById('qr-reader-results');

    status?.classList.remove('hidden');
    errorDiv?.classList.add('hidden');
    resultsEl.innerHTML = '';

    if (typeof Html5Qrcode === 'undefined') {
        resultsEl.innerHTML = '<p class="text-amber-600">Loading QR scanner library...</p>';
        return;
    }

    try {
        const cameras = await Html5Qrcode.getCameras();
        if (cameras.length === 0) {
            showCameraError('No camera detected on this device.');
            return;
        }

        selectSection?.classList.remove('hidden');
        select.innerHTML = cameras.map(c =>
            `<option value="${c.id}">${c.label || 'Camera ' + (cameras.indexOf(c) + 1)}</option>`
        ).join('');

        // Prefer back/environment camera
        const backCam = cameras.find(c =>
            c.label.toLowerCase().includes('back') ||
            c.label.toLowerCase().includes('rear') ||
            c.label.toLowerCase().includes('environment')
        );
        select.value = backCam ? backCam.id : cameras[0].id;

        await startCamera(select.value);
    } catch (err) {
        console.error('Camera init error:', err);
        showCameraError('Could not access camera. Please check permissions or use Upload QR Image / Manual Entry below.');
    }
}

async function startCamera(cameraId) {
    const readerEl = document.getElementById('qr-reader');
    const resultsEl = document.getElementById('qr-reader-results');
    const errorDiv = document.getElementById('camera-error');
    const toggleBtn = document.getElementById('toggle-camera-btn');

    errorDiv?.classList.add('hidden');
    resultsEl.innerHTML = '<p class="text-gray-400">Starting camera...</p>';

    // Clean up previous
    if (html5QrCode) {
        try { await html5QrCode.stop(); } catch(e) {}
        html5QrCode = null;
    }

    html5QrCode = new Html5Qrcode("qr-reader");

    try {
        await html5QrCode.start(
            { deviceId: cameraId },
            { fps: 10, qrbox: { width: 250, height: 250 } },
            onScanSuccess
        );
        isScanning = true;
        resultsEl.innerHTML = '<p class="text-emerald-600 text-xs">✓ Camera active — point at a QR code</p>';
        toggleBtn?.classList.remove('hidden');
        toggleBtn.innerHTML = '<svg class="w-4 h-4 inline mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg> Stop Camera';
        toggleBtn.className = 'px-4 py-2 text-sm font-medium rounded-xl transition bg-red-100 text-red-700 hover:bg-red-200';
        document.getElementById('camera-status-text').textContent = 'Camera active';
        document.getElementById('camera-dot').className = 'w-2 h-2 rounded-full bg-emerald-500 animate-pulse';
    } catch (err) {
        console.error('Camera start error:', err);
        readerEl.innerHTML = '';
        showCameraError('Could not start camera. Try selecting a different camera above, or use Upload QR Image / Manual Entry.');
    }
}

async function toggleCamera() {
    if (isScanning && html5QrCode) {
        try { await html5QrCode.stop(); } catch(e) {}
        html5QrCode = null;
        isScanning = false;
        document.getElementById('toggle-camera-btn').innerHTML = '<svg class="w-4 h-4 inline mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg> Start Camera';
        document.getElementById('toggle-camera-btn').className = 'px-4 py-2 text-sm font-medium rounded-xl transition bg-indigo-100 text-indigo-700 hover:bg-indigo-200';
        document.getElementById('camera-status-text').textContent = 'Camera stopped';
        document.getElementById('camera-dot').className = 'w-2 h-2 rounded-full bg-gray-400';
        document.getElementById('qr-reader').innerHTML = '';
    } else {
        const select = document.getElementById('camera-select');
        await startCamera(select?.value);
    }
}

function switchCamera() {
    const select = document.getElementById('camera-select');
    if (select?.value) startCamera(select.value);
}

function showCameraError(message) {
    document.getElementById('camera-error')?.classList.remove('hidden');
    document.getElementById('camera-error-message').innerHTML = message;
    document.getElementById('toggle-camera-btn')?.classList.add('hidden');
    document.getElementById('camera-status')?.classList.add('hidden');
    document.getElementById('qr-reader-results').innerHTML = '<p class="text-red-500 text-xs">Camera unavailable. Use Upload QR Image or Manual Entry below.</p>';
}

// ─── QR Scan Success ──────────────────────────────────────────────
let scanTimeout = null;

function onScanSuccess(decodedText) {
    if (scanTimeout) return;
    if (html5QrCode) { html5QrCode.pause(); }
    verifyTicket(decodedText);
    scanTimeout = setTimeout(() => {
        scanTimeout = null;
        if (html5QrCode) { html5QrCode.resume(); }
    }, 4000);
}

// ─── Upload QR Image ──────────────────────────────────────────────
async function scanQrFromImage(file) {
    const progress = document.getElementById('upload-progress');
    const fileName = document.getElementById('upload-file-name');
    const uploadArea = document.getElementById('upload-area');

    progress?.classList.remove('hidden');
    fileName.textContent = 'Scanning ' + file.name + '...';

    try {
        const result = await html5Qrcode.scanFile(file, true);
        progress?.classList.add('hidden');
        uploadArea?.classList.remove('border-emerald-300', 'bg-emerald-50/30');
        verifyTicket(result);
    } catch (err) {
        progress?.classList.add('hidden');
        document.getElementById('scan-result').innerHTML = `
            <div class="text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-8 h-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <h3 class="text-base font-semibold text-red-800 mb-1">No QR Code Found</h3>
                <p class="text-sm text-red-600">Could not detect a valid QR code in this image. Try a clearer photo or enter the ticket number manually.</p>
            </div>
        `;
    }
}

// ─── Verify Ticket ────────────────────────────────────────────────
async function verifyTicket(qrData) {
    const resultDiv = document.getElementById('scan-result');
    resultDiv.innerHTML = '<div class="text-center py-4"><svg class="animate-spin w-8 h-8 mx-auto text-indigo-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><p class="text-gray-500 mt-2">Verifying...</p></div>';

    try {
        const response = await fetch('{{ route('attendance.verify') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ qr_data: qrData })
        });

        const data = await response.json();

        if (data.success) {
            resultDiv.innerHTML = `
                <div class="text-center">
                    <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-emerald-800 mb-1">✓ Check-in Successful!</h3>
                    <p class="text-emerald-600 text-sm font-medium mb-4">${data.message}</p>
                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 text-left text-sm space-y-2">
                        <div class="flex justify-between"><span class="text-gray-500">Event:</span><span class="font-medium text-gray-900 text-right">${data.ticket.event?.title || 'N/A'}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Name:</span><span class="font-medium text-gray-900 text-right">${data.ticket.user?.name || 'N/A'}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Email:</span><span class="font-medium text-gray-900 text-right">${data.ticket.user?.email || 'N/A'}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Ticket:</span><code class="font-medium text-gray-900 text-right text-xs">${data.ticket.ticket_number}</code></div>
                    </div>
                </div>
            `;
        } else {
            resultDiv.innerHTML = `
                <div class="text-center">
                    <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-red-800 mb-1">Verification Failed</h3>
                    <p class="text-red-600 text-sm">${data.message}</p>
                    ${data.ticket ? `
                    <div class="mt-4 bg-red-50 border border-red-200 rounded-xl p-3 text-left text-sm space-y-1">
                        <p><span class="text-gray-500">Event:</span> <span class="font-medium">${data.ticket.event?.title || 'N/A'}</span></p>
                        <p><span class="text-gray-500">Name:</span> <span class="font-medium">${data.ticket.user?.name || 'N/A'}</span></p>
                        <p><span class="text-gray-500">Ticket:</span> <code class="font-medium">${data.ticket.ticket_number}</code></p>
                    </div>` : ''}
                </div>
            `;
        }
    } catch (error) {
        resultDiv.innerHTML = `
            <div class="text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-8 h-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                </div>
                <h3 class="text-base font-semibold text-red-800 mb-1">Connection Error</h3>
                <p class="text-sm text-red-600">Could not reach the server. Please try again.</p>
            </div>
        `;
    }
}

// ─── Init ─────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function() {
    // Load html5-qrcode library
    if (typeof Html5Qrcode === 'undefined') {
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js';
        script.onload = () => {
            // Create a scanner instance for image scanning
            html5QrCode = new Html5Qrcode("qr-reader");
            initCamera();
        };
        script.onerror = () => showCameraError('Failed to load QR scanner. Use Upload QR Image or Manual Entry below.');
        document.head.appendChild(script);
    } else {
        html5QrCode = new Html5Qrcode("qr-reader");
        initCamera();
    }

    // Camera selector
    document.getElementById('camera-select')?.addEventListener('change', function() {
        if (isScanning) startCamera(this.value);
    });

    // Manual submission
    document.getElementById('manual-verify-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const ticket = document.getElementById('manual-ticket').value.trim();
        if (ticket) { verifyTicket(ticket); }
    });

    // QR Image upload
    document.getElementById('qr-image-input').addEventListener('change', function(e) {
        const file = e.target.files?.[0];
        if (!file) return;
        document.getElementById('upload-file-name').textContent = file.name;
        scanQrFromImage(file);
        // Reset so the same file can be selected again
        this.value = '';
    });
});
</script>
@endpush
