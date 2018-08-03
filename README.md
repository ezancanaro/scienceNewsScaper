# scienceNewsScaper
Very basic PHP scraper for ScienceNews.org website based on topics.

## Resources 
cURL for requests and simple_php_dom for parsing the website. Basic responsive CSS adapted from http://getskeleton.com/.

## To-Do with more time:
1. Deploy in the web;
1. Improve layout: Use a template framework to build the html elements instead of using **echo** inside the code, separating view and controller. Decide on a proper visual style and create the css;
2. Use a database so we don't have to scrape the webpage on every access: Save the scraped articles in a database and show them from there. ScienceNews.org has a RSS feed we can use to keep it updated;
3. Add javascript and a button to keep loading articles dynamically;
3. Refactor some noisy code: Some sections were quite rushed and are kind of unpleasant to look at.
