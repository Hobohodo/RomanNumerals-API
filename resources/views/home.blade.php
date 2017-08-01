<?php
/**
 * Created by PhpStorm.
 * User: Thor
 * Date: 01/08/2017
 * Time: 21:50
 */
?>

<h1>Hello Numeral McNumberFace</h1>
<p>I am ready to serve</p>

<form action="/convert" method="post">
    {{csrf_field()}}
    <input type="number" aria-autocomplete="none" placeholder="Please enter a number sir">
</form>