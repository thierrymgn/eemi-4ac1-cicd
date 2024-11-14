WHITE='\033[0m'
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'

outputTitle()
{
	title=$1
	echo -e "${YELLOW}=== $title ===${WHITE}"
}

outputSubTitle()
{
	title=$1
	echo -e "-- $title"
}

checkScript() {
	command=$1
	expectedResponse=$2
	actualResponse="$($command)"

	if [[ ($expectedResponse == "" && -z "$actualResponse") || ($expectedResponse != "" && "$actualResponse" =~ $expectedResponse) ]]
	then
		echo -e "${GREEN}OK${WHITE}\n"
	else
		echo -e "${RED}Failed${WHITE}"
		echo $actualResponse
		exit 1
	fi
}

outputTitle "PHP_CodeSniffer"
outputSubTitle "PHP_CodeSniffer tokenizes PHP, JavaScript and CSS files to detect and correct coding standard violations."
checkScript "vendor/bin/phpcs --ignore=./src/Kernel.php ./src -s" ""
checkScript "vendor/bin/phpcs --ignore=./tests/bootstrap.php ./tests -s" ""

outputTitle "PHPStan"
outputSubTitle "PHPStan focuses on finding errors in your code without actually running it."
# Set on max level, c.f. https://phpstan.org/user-guide/rule-levels
checkScript "vendor/bin/phpstan --level=9 analyse src" " \[OK\] No errors"
checkScript "vendor/bin/phpstan --level=9 analyse tests" " \[OK\] No errors"