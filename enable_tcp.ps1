Import-Module SQLPS -DisableNameChecking
$smo = 'Microsoft.SqlServer.Management.Smo.'
$wmi = New-Object ($smo+'Wmi.ManagedComputer') '.'
$uri = "ManagedComputer[@Name='$($wmi.Name)']/ServerInstance[@Name='MSSQLSERVER']/ServerProtocol[@Name='Tcp']"
$Tcp = $wmi.GetSmoObject($uri)
$Tcp.IsEnabled = $true
$Tcp.Alter()
Write-Output 'TCP/IP enabled successfully. Restart SQL Server service to apply.'
