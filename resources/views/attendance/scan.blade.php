@extends('layouts.app')

@section('title', 'Scan QR Tickets - ' . $event->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <a href="{{ route('events.show', $event) }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Event
        </a>
    </div>

    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Scan Tickets</h1>
        <p class="text-gray-500 mt-1">{{ $event->title }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- QR Scanner -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <svg class="w-5 h-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                </svg>
                Scan QR Code
            </h2>
            <div id="qr-reader" class="w-full max-w-sm mx-auto"></div>
            <div id="qr-reader-results" class="mt-4 text-center text-sm text-gray-500"></div>

            <!-- Manual Entry Fallback -->
            <div class="mt-6 pt-6 border-t border-gray-100">
                <p class="text-sm font-medium text-gray-700 mb-3">Or enter ticket number manually</p>
                <form id="manual-verify-form" class="flex gap-2">
                    @csrf
                    <input type="text" id="manual-ticket" placeholder="Enter ticket number..."
                        class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    <button type="submit" class="px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                        Verify
                    </button>
                </form>
            </div>
        </div>

        <!-- Scan Result -->
        <div class="space-y-4">
            <h2 class="text-lg font-semibold text-gray-900">Recent Verifications</h2>
            <div id="scan-result" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="text-center py-8 text-gray-400">
                    <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                    <p>Scan a QR code or enter a ticket number to verify</p>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between text-sm">
                    <div class="text-center px-4">
                        <p class="text-2xl font-bold text-gray-900" id="checked-count">0</p>
                        <p class="text-gray-500">Checked In</p>
                    </div>
                    <div class="border-r border-gray-200 h-10"></div>
                    <div class="text-center px-4">
                        <p class="text-2xl font-bold text-gray-900" id="total-count">0</p>
                        <p class="text-gray-500">Total</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let html5QrCode = null;

function startScanner() {
    if (typeof Html5Qrcode === 'undefined') {
        setTimeout(startScanner, 500);
        return;
    }

    html5QrCode = new Html5Qrcode("qr-reader");

    html5QrCode.start(
        { facingMode: "environment" },
        {
            fps: 10,
            qrbox: { width: 250, height: 250 }
        },
        onScanSuccess
    ).catch(err => {
        document.getElementById('qr-reader-results').innerHTML =
            '<p class="text-red-500">Camera access denied or unavailable. Use manual entry below.</p>';
    });
}

function onScanSuccess(decodedText) {
    if (html5QrCode) {
        html5QrCode.pause();
    }
    verifyTicket(decodedText);
    setTimeout(() => {
        if (html5QrCode) {
            html5QrCode.resume();
        }
    }, 3000);
}

async function verifyTicket(qrData) {
    const resultDiv = document.getElementById('scan-result');
    resultDiv.innerHTML = '<div class="text-center py-4"><svg class="animate-spin w-8 h-8 mx-auto text-indigo-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><p class="text-gray-500 mt-2">Verifying...</p></div>';

    try {
        const response = await fetch('{{ route('attendance.verify', $event) }}', {
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
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-green-800 mb-1">Check-in Successful!</h3>
                    <p class="text-green-600 font-medium">${data.message}</p>
                    <div class="mt-4 p-3 bg-gray-50 rounded-lg text-left text-sm">
                        <p><span class="text-gray-500">Name:</span> <span class="font-medium">${data.ticket.user.name}</span></p>
                        <p><span class="text-gray-500">Email:</span> <span class="font-medium">${data.ticket.user.email}</span></p>
                        <p><span class="text-gray-500">Ticket:</span> <span class="font-medium">${data.ticket.ticket_number}</span></p>
                    </div>
                </div>
            `;
        } else {
            resultDiv.innerHTML = `
                <div class="text-center">
                    <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-red-800 mb-1">Verification Failed</h3>
                    <p class="text-red-600">${data.message}</p>
                    ${data.ticket ? `
                    <div class="mt-4 p-3 bg-gray-50 rounded-lg text-left text-sm">
                        <p><span class="text-gray-500">Name:</span> <span class="font-medium">${data.ticket.user?.name || 'N/A'}</span></p>
                        <p><span class="text-gray-500">Ticket:</span> <span class="font-medium">${data.ticket.ticket_number}</span></p>
                    </div>` : ''}
                </div>
            `;
        }

        // Update stats
        updateStats();
    } catch (error) {
        resultDiv.innerHTML = `
            <div class="text-center">
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-red-800 mb-1">Error</h3>
                <p class="text-red-600">Could not verify ticket. Please try again.</p>
            </div>
        `;
    }
}

async function updateStats() {
    try {
        const response = await fetch(window.location.href + '?stats=1');
        // Simple approach: just reload counts from DOM or a lightweight endpoint
    } catch (e) {}
}

document.addEventListener('DOMContentLoaded', function() {
    startScanner();

    // Manual ticket verification
    document.getElementById('manual-verify-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const ticket = document.getElementById('manual-ticket').value.trim();
        if (ticket) {
            verifyTicket(ticket);
        }
    });
});
</script>
@endpush
