param(
    [int]$UserId = 1
)

$base = 'http://localhost:8000'
$cookieJar = "c:\\pergel\\tools\\cookie.txt"

function Invoke-Get([string]$uri){
    try{
        $req = Invoke-WebRequest -UseBasicParsing -Uri $uri -SessionVariable s -TimeoutSec 15
        return @{ Status = 'OK'; Response = $req }
    } catch {
        return @{ Status = 'ERROR'; Message = $_.Exception.Message }
    }
}

# Login-as
$loginUri = "$base/__dev/login-as/$UserId"
$login = Invoke-Get -uri $loginUri
if ($login.Status -ne 'OK') { Write-Host "Login-as failed: $($login.Message)"; exit 1 }
Write-Host "Login-as response status: $($login.Response.StatusCode)"

# Now access /tasks with the same session
try{
    $tasks = Invoke-WebRequest -UseBasicParsing -Uri "$base/tasks" -WebSession $s -TimeoutSec 15
    Write-Host "Tasks page status: $($tasks.StatusCode)"
} catch {
    Write-Host "Tasks request failed: $($_.Exception.Message)"
}
