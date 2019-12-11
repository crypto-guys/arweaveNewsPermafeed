# arweaveNewsPermafeed
The bot retrieves news headlines by keyword from newsapi.org 

Data is archived at this address [iie7jUhFrlN4sV8UppcI7Fc5mN07XDvvgQ0R53fugcU](https://viewblock.io/arweave/address/iie7jUhFrlN4sV8UppcI7Fc5mN07XDvvgQ0R53fugcU)

# Requirements
An api key from [newsapi.org](https://www.newsapi.org)

A linux host with php 

# Installation
mkdir /arweaveNewsPermafeed

clone this repo to /arweaveNewsPermafeed

sudo apt install php7.2-bcmath php7.2-bz2 php7.2-cli php7.2-common php7.2-curl php7.2-gd php7.2-gmp php7.2-intl php7.2-json php7.2-mbstring php7.2-xml php7.2-zip 

# Usage
run ./config.sh to configure the bot. 

Specify wallet, api key, run schedule, and keyword

This will automatically schedule the bot either hourly, twice daily, or daily

I am currently archiving results for these keywords = sports, breaking  

# Update 12-11-2019
It looks like the newsapi service has stopped providing new results. Newsapi.org has been notified. The script was modified to abort if no results were obtained so it will appear as though the script has stopped running. New data will be posted to arweave once the api service is functioning again.

https://newsapi.org/v2/everything?apiKey=d2da966fd1d44b02a1bf6c3f8c3def53&q=world&from=2019-12-11

Provides no results at this time.


# Examples
# ALL
    {
    op: 'and',
    expr1: {
    op: 'equals',
    expr1: 'from',
    expr2: 'iie7jUhFrlN4sV8UppcI7Fc5mN07XDvvgQ0R53fugcU'
    },
    expr2: {
    op: 'equals',
    expr1: 'App-Name',
    expr2: 'permaNewsfeed'
    }
    }
    
# Breaking headlines
    {
    op: 'and',
    expr1: {
    op: 'equals',
    expr1: 'from',
    expr2: 'iie7jUhFrlN4sV8UppcI7Fc5mN07XDvvgQ0R53fugcU'
    },
    expr2: {
    op: 'equals',
    expr1: 'App-Name',
    expr2: 'permaNewsfeed'
    },
    expr3: {
    op: 'equals',
    expr1: 'keyword',
    expr2: 'breaking'
    }
    }
    
# Sports headlines
    {
    op: 'and',
    expr1: {
    op: 'equals',
    expr1: 'from',
    expr2: 'iie7jUhFrlN4sV8UppcI7Fc5mN07XDvvgQ0R53fugcU'
    },
    expr2: {
    op: 'equals',
    expr1: 'App-Name',
    expr2: 'permaNewsfeed'
    },
    expr3: {
    op: 'equals',
    expr1: 'keyword',
    expr2: 'sports'
    }
    }
