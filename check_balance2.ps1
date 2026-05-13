cd$content = Get-Content resources/views/layouts/nav.blade.php
$depth = 0
$extraEndif = @()
$missingEndifLines = @()
for ($i = 0; $i -lt $content.Count; $i++) {
    $line = $content[$i]
    # Skip lines inside HTML comments
    if ($line -match '^\s*<!--') { continue }
    if ($line -match '-->\s*$') { continue }
    # Check for @if or @endif at the start of the line (after optional whitespace)
    $trimmed = $line.TrimStart()
    if ($trimmed -match '^@if\b') {
        $depth++
    } elseif ($trimmed -match '^@endif\b') {   
        if ($depth -le 0) {
            $extraEndif += ($i + 1)
        }
        $depth--
    }
}
Write-Host "Final depth: $depth"
Write-Host "Extra @endif at lines: $($extraEndif -join ', ')"
