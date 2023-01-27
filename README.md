# DrSql
PHP Resource to prevent SQL injection tries
# How to use?
To be able to use this resource as desired, follow the following documentation:
# Checking if there's SQL injection try using GET request headers:
1. Include DrSql.php
2. Require the check function by this way: omCheck(1,0): (First argument for GET and the second for POST)
3. If you're using DrSql with Direct Block option enabled you don't have to do anything further, DrSql will block the request automatically if there's any tries to inject anything through your SQL DB.
4. If you want to retrieve the tries count and details, Turn Direct Block option off, then you will get a return like this: <img width="950" alt="image" src="https://user-images.githubusercontent.com/75754684/215020786-d8f686d6-b3e3-4cfb-9a71-813b1c2d26dd.png">.
5. For POST check do the same but require the function this way: omCheck(0,1).
6. For the both POST and GET require the function this way: omCheck(1,1).
5. If you want the tool to run automatically on every request when you include it set checkWithoutRequire option to on, but notice: this will check always for the both POST and GET.
