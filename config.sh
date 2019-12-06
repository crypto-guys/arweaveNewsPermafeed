#/bin/bash

# make directory to store temporary files
#mkdir ./tmp

# promt user to paste arweave wallet file and save it to jwk.json
echo "Please paste the contents of your arweave wallet key file"
read walletkey
echo $walletkey > jwk.json
#openssl enc -aes-256-cbc -salt -in jwk.json -out jwk.json.enc
#rm jwk.json

# enter a keyword to search
echo "Please enter your keyword to search"
read keyword
echo $keyword > keyword.txt

# enter number of results to return max 100
echo "Please enter the number of results to return max 100"
read results
echo $results > results.txt

# accept user input in hours only whole numbers accepted save to hours.txt
echo "Please choose a schedule"
echo "1. Hourly"
echo "2. Twice Daily"
echo "3. Daily"
read frequency
echo $frequency > schedule.txt

# accept user input for api key save to apikey.txt
echo "Please enter your api key"
read apikey
echo $apikey > apiKey.txt

# schedule crontab to run main script per schedule.txt and output to logfile
if [[ $frequency = '1' ]]; then
  crontab -l | { cat; echo "0 * * * * php -f ~/arweaveNewsPermafeed/bot.php >> /arweaveNewsPermafeed/bot.log"; } | crontab -
fi
if [[ $frequency = '2' ]]; then
  crontab -l | { cat; echo "0 3,15  * * * * php -f ~/arweaveNewsPermafeed/bot.php >> /arweaveNewsPermafeed/bot.log"; } | crontab -
fi
if [[ $frequency = '3' ]]; then
  crontab -l | { cat; echo "0 12 * * * php -f ~/arweaveNewsPermafeed/bot.php >> /arweaveNewsPermafeed/bot.log"; } | crontab -
fi
