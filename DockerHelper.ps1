
# Invoke-Item C:\Windows\System32\WindowsPowerShell\v1.0\powershell.exe;
$projectPath = $Env:Programfiles + "\Docker Toolbox\"
$projectName = "silex-ekookna"
invoke-expression "cmd /c start powershell -Command { cd '$projectPath'; .\start.sh; cd '$projectPath$projectName'; docker-compose up  }"
invoke-expression "cmd /c start powershell -Command { cd '$projectPath$projectName'; docker-compose up }"

$projectPath = $Env:Programfiles + "\Docker Toolbox\silex-ekookna\"
cd $projectPath
docker-compose.exe up

$projectPath = $Env:Programfiles + "\Docker Toolbox\silex-ekookna\www\"
cd $projectPath
docker cp ./ silexekookna_www_1:/var/www/html/

$VSpath = $env:APPDATA + '\..\Local\Programs\Microsoft VS Code\Code.exe'
Invoke-Item $VSpath

$URL = 'http://192.168.99.100:8001/'
[system.Diagnostics.Process]::Start("firefox",$URL)

$URL = 'http://192.168.99.100:8000/'
[system.Diagnostics.Process]::Start("firefox",$URL)

#
#[system.Diagnostics.Process]::Start("TOTALCMD64",$URL)
#
#invoke-expression "cmd /c start powershell -NoExit -Command { cd '$projectPath$projectName';docker exec -it $($projectName)_1 /bin/bash }"
#