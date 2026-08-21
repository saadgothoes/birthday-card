param(
    [string]$ExePath = "C:\wamp64\www\birthday-card\storage\app\mailpit\mailpit.exe",
    [string]$SmtpAddr = '127.0.0.1:1025',
    [string]$HttpAddr = '127.0.0.1:8025'
)

if (-not (Test-Path $ExePath)) {
    Write-Host "Mailpit binary not found at: $ExePath" -ForegroundColor Yellow
    Write-Host "Download the Windows binary and place it at that path:" -ForegroundColor Yellow
    Write-Host "https://github.com/axllent/mailpit/releases/latest/download/mailpit_windows_amd64.exe" -ForegroundColor Cyan
    exit 1
}

Write-Host "Starting Mailpit: $ExePath" -ForegroundColor Green
Start-Process -FilePath $ExePath -ArgumentList "--smtp", $SmtpAddr, "--listen", $HttpAddr -NoNewWindow -PassThru | Out-Null
Start-Sleep -Seconds 1

# Verify ports
try {
    $listening = Get-NetTCPConnection -LocalPort 1025,8025 -State Listen -ErrorAction Stop
    if ($listening) {
        Write-Host "Mailpit started and listening on ports 1025 and 8025." -ForegroundColor Green
        Write-Host "Open the UI at http://127.0.0.1:8025/" -ForegroundColor Cyan
    }
} catch {
    Write-Host "Could not detect Mailpit listening yet. Check the process or run the binary manually." -ForegroundColor Yellow
}
