Try {
    $r = Invoke-WebRequest -UseBasicParsing -Uri 'http://localhost:8000/__dev/create-test-user' -Method GET -Headers @{'Accept'='application/json'} -TimeoutSec 15 -ErrorAction Stop
    Write-Output "HTTP_STATUS: $($r.StatusCode.Value__)"
    Write-Output $r.Content
} Catch {
    Write-Output 'REQUEST_FAILED: ' + $_.Exception.Message
    if ($_.Exception.Response) {
        $resp = $_.Exception.Response
        Write-Output 'HTTP_STATUS: ' + $resp.StatusCode.Value__
        $sr = New-Object System.IO.StreamReader($resp.GetResponseStream())
        Write-Output $sr.ReadToEnd()
    }
    exit 1
}
