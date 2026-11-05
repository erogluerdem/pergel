# Setup script for Pergel project (Windows PowerShell)
# Usage: Open PowerShell as Administrator (if necessary) and run: .\setup-project.ps1

Write-Host "Starting project setup..." -ForegroundColor Cyan

# Ensure composer is available
if (-not (Get-Command composer -ErrorAction SilentlyContinue)) {
    Write-Host "Composer not found in PATH. Please install Composer first: https://getcomposer.org/" -ForegroundColor Red
    exit 1
}

# 1) Install PHP dependencies
Write-Host "Running composer install..." -ForegroundColor Yellow
composer install --no-interaction
if ($LASTEXITCODE -ne 0) { Write-Host "composer install failed" -ForegroundColor Red; exit $LASTEXITCODE }

# 2) Copy .env if missing
if (-not (Test-Path .env)) {
    if (Test-Path .env.example) {
        Copy-Item .env.example .env -Force
        Write-Host ".env copied from .env.example" -ForegroundColor Green
    } else {
        Write-Host ".env.example not found. Create .env manually and set DB connection." -ForegroundColor Red
        exit 1
    }
} else {
    Write-Host ".env already exists" -ForegroundColor Green
}

# 3) Generate app key
Write-Host "Generating app key..." -ForegroundColor Yellow
php artisan key:generate

# 4) Run migrations and seed HR expenses only (safe)
Write-Host "Running migrations..." -ForegroundColor Yellow
php artisan migrate --force
if ($LASTEXITCODE -ne 0) { Write-Host "migrate failed" -ForegroundColor Red; exit $LASTEXITCODE }

Write-Host "Seeding HrExpense sample data..." -ForegroundColor Yellow
php artisan db:seed --class=HrExpenseSeeder
if ($LASTEXITCODE -ne 0) { Write-Host "seeder failed" -ForegroundColor Red; exit $LASTEXITCODE }

Write-Host "Setup complete. You can now run: php artisan serve" -ForegroundColor Green

exit 0
