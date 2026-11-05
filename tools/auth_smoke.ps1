# Authenticated smoke tests: create a dev user, login-as, then fetch a list of pages and print status codes
param(
    [string]$Base = 'http://localhost:8000'
)

function Ensure-Server {
    param($base)
    try {
        Invoke-WebRequest -UseBasicParsing -Uri $base -Method Head -TimeoutSec 5 | Out-Null
        return $true
    } catch {
        Write-Host "Server not responding at $base. Attempting to start PHP dev server..."
        $php = (Get-Command php).Source
        if (-not $php) { Write-Host 'PHP CLI not found in PATH'; exit 2 }
        Start-Process -NoNewWindow -FilePath $php -ArgumentList '-S localhost:8000 -t public' | Out-Null
        Start-Sleep -Seconds 2
        # retry
        try { Invoke-WebRequest -UseBasicParsing -Uri $base -Method Head -TimeoutSec 5 | Out-Null; return $true } catch { Write-Host 'Failed to reach dev server after start'; return $false }
    }
}

if (-not (Ensure-Server -base $Base)) { Write-Host 'Dev server not available; aborting tests.'; exit 3 }

# Create test user
Write-Host 'Creating test user...'
try {
    $r = Invoke-WebRequest -UseBasicParsing -Uri "$Base/__dev/create-test-user" -Method GET -TimeoutSec 15
    $content = $r.Content.Trim()
    Write-Host "Create response: $content"
    $json = $null
    try { $json = ConvertFrom-Json $content } catch { }
    if (-not $json -or -not $json.id) { Write-Host 'Failed to create test user (no id returned)'; exit 4 }
    $userId = $json.id
} catch {
    Write-Host 'Create-test-user request failed: ' $_.Exception.Message
    exit 5
}

# Login-as and keep session
Write-Host "Logging in as user $userId..."
try {
    $login = Invoke-WebRequest -UseBasicParsing -Uri "$Base/__dev/login-as/$userId" -Method GET -SessionVariable s -TimeoutSec 15
    Write-Host "Login response status: $($login.StatusCode)"
} catch {
    Write-Host 'Login-as failed: ' $_.Exception.Message
    exit 6
}

$pages = @(
    '/',
    '/tasks',
    '/tasks/create',
    '/messages',
    '/notes',
    '/activities',
    '/apps-contact',
    '/portfolio/management',
    '/projects',
    '/search'
)

Write-Host "\nAuthenticated smoke test results (using user id: $userId):\n"
foreach ($p in $pages) {
    $uri = "$Base$p"
    try {
        $resp = Invoke-WebRequest -UseBasicParsing -Uri $uri -WebSession $s -TimeoutSec 15
        $code = $resp.StatusCode
        Write-Host "$p -> $code"
    } catch {
        # Try to detect redirect location
        $msg = $_.Exception.Message
        Write-Host "$p -> REQUEST_FAILED: $msg"
    }
}

Write-Host "\nSmoke tests completed."
