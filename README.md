matcher
=======

The problem
--------------

We have two databases with products. The first is the client's product's database and the other formed by their competitors products. Both have a field that link the perfect matches, for validation. 

The job
--------------

The job is to search for the products to find the better match, comparing informations. After that, to validate the results with the matching code of the competitor base.

The way
--------------

Using the **Laravel PHP Framework** as a base, I've created a simple *REST service* which receives the product and compares it by the title similarity, using the PHP functions *similar_text* and *levenshtein*.

First the algorithm breaks the product title and compares each word with the competitor's product's title using *similar_text* function. This phase accepts only 100% word matches to calculate a percentage of similarity of the titles, I call it *similarity by word*. 

Then it compares the whole title similarity using the *similar_text* function. A product can passes this phase if it have until 65% of similarity.

The last step is to get only the results which passes the tests of *similarity by word* and *similarity by whole title* and sort it using the data of *similarity by word*, *levenshtein* and *similar_text*, resulting in the better match to the product.

*See functional version at http://mbeck.com.br/matcher/*
