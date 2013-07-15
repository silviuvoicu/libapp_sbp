This is copycat in symfony2, behat, mink, phpspec2 for this https://github.com/Remchi/libapp 
which is made with ruby on rails, cucumber, rspec. 
It's a work in progress, it's not 100 % copied, or just to say not yet similiar to. 
I get also my inspiration from sylius project, and of course from the documentation of 
symfony2, behat, mink, phpspec2. 
I hope to finish this repo at same level as his cousin :)

Now either, clone or install/create a new project for symfony2 version 2.2
add in the composer.json the following lines, before scripts
     
      "require-dev": {
        "behat/behat":                       "2.4.*",
        "behat/symfony2-extension":          "*",
        "behat/mink-extension":              "*",
        "behat/mink-browserkit-driver":      "*",
        "phpspec/phpspec2":                  "dev-develop",
        "behat/mink-selenium2-driver":       "*"
    }

And then using composer, install also the dev requirements.

