# automation-clickadu
Purpose
- This is a test repository for test

SET UP
1) On system where you want run tests, you should have php 7.1+, git and composer.
2) Clone this repo 

``` git clone git@github.com:MargaritaDenisenko/automation-clickadu.git```

3) Run in the project folder

```composer update```

For running tests you need to install:

1. Create selenium folder (it is not necessary to create it in the project folder)
2. Download Selenium Server https://www.seleniumhq.org/download/
3. Download ChromeDriver https://sites.google.com/a/chromium.org/chromedriver/downloads
4. Move them to your selenium folder
5. In the selenium folder create start-selenium-server.sh file with this script inside ```java -Dwebdriver.chrome.driver=chromedriver -jar selenium-server-standalone-3.14.0.jar```

6. Do start-selenium-server.sh file executable: on Linux ```chmod ug+x start-selenium-server.sh```, on MacOS ```chmod +x start-selenium-server.sh```
7. In terminal you should run ```./start-selenium-server.sh```. If you do all correctly you will see that selenium is up and running
8. Go to http://localhost:4444/ (it should work if selenium is running)
9. In the project folder set up behat/mink-selenium2-driver: ```composer require behat/mink-selenium2-driver```
10. To run tests run this command in the project folder: ```vendor/bin/behat```
