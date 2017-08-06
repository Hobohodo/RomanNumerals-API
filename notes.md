#Task notes

This is just a summary of my thoughts while doing the test.

##Usage

There are 3 endpoints to the given API:
1) `convert` will take a variable `integer` and return the provided integer and a roman numeral.
2) `recent` will list all recently converted integers. You can pass through a `period` variable of day, week or month to 
change the time limit.
3) `common` will list the 10 most commonly converted integers. You can send `timestamps = true` to view when these were 
most recently converted.

##Thoughts

For the database I only created the one "conversions" table, and used an index to make finding rows based on the integers 
easy. I considered using multiple tables for this - one to act as a cache of converted integers (with the most recently 
converted date) and another to store the counts, but realised this would fail to show an accurate history if the same integer 
were converted multiple times.

I set the number of characters for the "numeral" field to 32, but did cheat a bit and find that the longest possible 
conversion was from 3888 with 15 characters at MMMDCCCLXXXVIII. It might then have made more sense to set the limit for 
the numeral column to '15', however I didn't really think it was a necessary optimisation.

When I had everything set up the biggest questions were ones of which route to take, which to be fair is almost always the case.
I opted to not have 2 tables for storing the conversion and most recent date time and probably made several other decisions 
not for optimisation but to actually have something to work from.

##Where I struggled

The most challenging aspect of this test was actually not the development itself, but rather setting up the environment. 
Due to some misunderstandings of the Laravel/Homestead vagrant box and then some strange issues with windows well over half
 the time spent was on getting something set up for testing in the first place.

IDE support for laravel isn't excellent, so another big time sink was sorting out which functions did and didn't exist in 
each class. I ended up getting a composer package which solved most of the issues.

In general it was tricky for me to decide where bits of logic should go. I tried to go with leaving all models in the "app" 
folder as is Laravel's default, but I did find it harder to decide what was worthy of being a model (if I were to do it again 
I would probably namespace models and place them in folders e.g. Entities, Validation, Responses folders).

Eloquent's way of working with models is actually very different to what I expected, and I found myself pausing a lot while 
working with the models. This was due to the lack of field definitions, and ways of interacting with the data when you 
retrieve it from the model. This is quite easily solved with phpdoc blocks/experience, however, and likely a matter of inexperience.

##What I would do differently if starting over

* I would most definitely not name the conversion totals table "total" and give the column name "total". It just made naming 
unnecessarily complex. 

* Create more 'services' and put less logic in the controller.

* In a similar vein, create either a dedicated validation Model or just have clearer validation functions. I avoided 
changing the provided interface as much as possible, which lead to the validation being in strange places.

* Probably make separate transformers for timestamps, as the current method places it inside another array - good if it 
were expanded into more generic "metadata", but not good for the current API endpoints

* When creating the endpoints I would probably make use of the ability to take variables from URLs in routes, e.g. `Route::get('/convert/{integer}', "ConvertController@convert")->where('integer', '[0-9]+');`

* Create a 'unique' option when listing recent transactions, to select from the Totals table instead of the conversions.

* Make use of pagination/variable limits for the common and recent endpoints.

* Use branches in git, I didn't due to the size of the test but should have done to keep better track of changes.

##Running commentary
This is a list of thoughts and comments done while writing the test, initially imported from OneNote.

The first half is a long list of failures, with some more constructive thoughts later on.

* My available options are:
  * Host remotely, set up my git repo there and push to it. 
  	* Use a bare repo and a clone that's used to display the site?
  * Set up Vagrant box or php in windows and run locally - may be an issue with sorting out db things.

* Decided to use virtualbox and laravel\homestead to spin up a remote box.

* After creating vagrant box was unable to retrieve a response from the server, this was due to laravel not being installed 
in the vendor folder. (composer issue)

* Installed composer on windows due to ease of use in future, sadly not compatible with the remote interpreter in PHPStorm.

* Installed PHP 7.1 interpreter, added PHP to the PATH and enabled extensions with some edits to the php.ini

* When running composer discovered an error where Symfony's HttpKernel was not installed correctly - it exists in 
vendor/composer/b037c860/symfony-http-kernel-c830387/HttpKernelInterface.php but not in vendor/symfony.

* This was due to an issue I thought was solved earlier where running php artisan optimize failed (php not defined). I 
thought this was fixed by adding php to the PATH since retrying composer did not fail again, but evidently not. Attempted 
solution: restart computer.

* Solution did not work, turns out I messed up when adding php to the PATH. Composer now installs fine.

* discovered I had not yet enabled virtualization in my BIOS, changed motherboards since the last time working with VMs.

* I used the Homestead/Laravel composer component to generate a vagrantfile, and the app is accessible via SSH using localhost:2222

* _At this point I didn't realise I hadn't actually got the server running and thought the issue may have been to do with 
there not being a default landing page_ Currently working on which class is needed for Route, since there are many possible answers.

Here is where I gave up on Laravel/Homestead. I didn't write anything in the log on tuesday while working this out.

* Tuesday 01/08/17 21:25pm Finally got a server running that shows something! Gave up on homestead/laravel completely, tried 
running with php artisan serve

* Ran into an issue where everything was returning "not found" errors, found laravel.log and it was pointing to index.php then to the compiled files

```
[2017-08-0120:21:26]production.ERROR:RuntimeException:TheonlysupportedciphersareAES-128-CBCandAES-256-CBCwiththecorrectkeylengths.inD:\Documents\Projects\RomanNumerals-API\bootstrap\cache\compiled.php:13520
   Stacktrace:
   #0D:...\RomanNumerals-API\bootstrap\cache\compiled.php(7927):Illuminate\Encryption\Encrypter->__construct('','AES-256-CBC')
   #1D:...\RomanNumerals-API\bootstrap\cache\compiled.php(1477):Illuminate\Encryption\EncryptionServiceProvider->Illuminate\Encryption\{closure}(Object(Illuminate\Foundation\Application),Array)
   #2D:...\RomanNumerals-API\bootstrap\cache\compiled.php(1433):Illuminate\Container\Container->build(Object(Closure),Array)
   #3D:...\RomanNumerals-API\bootstrap\cache\compiled.php(2011):Illuminate\Container\Container->make('encrypter',Array)
   #4D:...\RomanNumerals-API\bootstrap\cache\compiled.php(1534):Illuminate\Foundation\Application->make('encrypter')
   #5D:...\RomanNumerals-API\bootstrap\cache\compiled.php(1511):Illuminate\Container\Container->resolveClass(Object(ReflectionParameter))
   #6D:...\RomanNumerals-API\bootstrap\cache\compiled.php(1497):Illuminate\Container\Container->getDependencies(Array,Array)
   #7D:...\RomanNumerals-API\bootstrap\cache\compiled.php(1433):Illuminate\Container\Container->build('App\\Http\\Middle...',Array)
   #8D:...\RomanNumerals-API\bootstrap\cache\compiled.php(2011):Illuminate\Container\Container->make('App\\Http\\Middle...',Array)
   #9D:...\RomanNumerals-API\bootstrap\cache\compiled.php(2529):Illuminate\Foundation\Application->make('App\\Http\\Middle...')
   #10D:...\RomanNumerals-API\public\index.php(58):Illuminate\Foundation\Http\Kernel->terminate(Object(Illuminate\Http\Request),Object(Illuminate\Http\Response))
   #11D:...\RomanNumerals-API\server.php(21):require_once('D:\\Documents\\Pr...')
   #12{main}
```

* Did the obvious and googled the exception, discovered that this was likely due to a lack of an application key, and ran `php artisan key:generate`

* Created a home template in blade, it was refreshingly simple to do.

* Installed fractal using composer as it wasn't pre-installed. Not updated the composer.json or composer.lock files though.

* Created a numerals table using artisan migrate, I had to change the environment to work on root though (no changes required for other things).

* In implementing the Roman Numeral conversion, I would have placed the type checking of the value in the function arguments
 using scalar type hinting, but decided to obey the interface as given.

* I was initially going to have the Numerals table use an "index" on the integer column. This would have interfered with 
the requirement to view the most recently stored integers, and so was removed.

* I also initially created the "Numerals" table using `php artisan make:migration create_numerals_table --table=numerals` 
instead of using php `model:make model Numerals -m`. This wasn't really a mistake, just inefficient.

* Realised that effective use of Fractal resources would make it much easier to work with a `totals` table, so going to make one now.

* Now using the Fractal Transformers to return data - the ConversionTransformer works better just returning the integer 
and roman numerals, but the client may want to view the "most recent" data as well. It looks like this would be best dealt 
with using the "includes" method from Fractal\Transformer.

* Created a TimestampTransformer that can work for any Eloquent model - gated by the Parent Transformers at the moment.

* After adding the ability to specify a time period for the recent conversions, I've had a look at the controller and really
 don't like how messy the code is. I'm not sure whether to create a service for recent conversions, or to try and leave it as it is.

* Left the time period logic in the controller, it's definitely the worst code there - should probably use something in the 
constants to help with the logic and either have a foreach() loop over valid time periods or use an associative array and just find the index.

* Removed blade templates as everything is now working using JSON.

* Decided to look up Exception Handling and found a much nicer way using Laravel - tried to implement that