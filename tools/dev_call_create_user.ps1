Try {
    $r = Invoke-WebRequest -UseBasicParsing -Uri 'http://localhost:8000/__dev/create-test-user' -Method GET -Headers @{'Accept'='application/json'} -TimeoutSec 15
    $r.Content | Out-Host
} Catch {
    Write-Host 'REQUEST_FAILED:' $_.Exception.Message
    exit 1
}
