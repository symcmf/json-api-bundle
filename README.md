JSONApi bundle
========================
## Setup development environment with Homestead 

1. Clone project

    with SSH

    ```
        git clone git@github.com:symcmf/json-api-bundle
    ```
    
    or with HTTPS
    
    ```
        git clone https://github.com/symcmf/json-api-bundle
    ```
	
	and switch current brunch to 'develop'
	
	 ```
      git checkout develop
     ```
	
2. Run composer
   
    ```
       composer install 
    ```
3. Setup homestead/vagrant environment in project folder:
	
    ```
    ./vendor/bin/homestead make
	```

4. Edit Homestead.yaml:
    > Remove the following lines from Homestead.yaml if you don't have this SSH keys on your machine (https://laravel.com/docs/master/homestead#installation-and-setup):
	> Or generate and paste your SSH keys.
    
    ```
    authorize: ~/.ssh/id_rsa.pub
    keys:
        - ~/.ssh/id_rsa
     ```
     
     > Set type option that tells Homestead to use the Symfony nginx configuration.
     
     ```
    sites:
        - map: homestead.app
          to: "/home/vagrant/yourprojectfolder/web"
          type: symfony
    ```

5. Run vagrant
	
    ```
    vagrant up
    ```
6. Run migrations
   
    ```
     ./bin/db.sh
    ```
    
    > If you will get error "Permission denied" you have to change access rules with next command 
        
    ```
        sudo chmod -R 777 bin/
     ```
     
7. After it, browse [http://192.168.10.10](http://192.168.10.10), you should see the main page of application.
   Or add to your hosts file 
    
     ```
        192.168.10.10  homestead.app
     ```
   
     and browse [http://homestead.app](http://homestead.app).