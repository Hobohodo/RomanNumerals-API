<h1>Hello Numeral McNumberFace</h1>
<p>I am ready to serve</p>

<form action="/convert" method="post">
    {{csrf_field()}}
    <input type="number" aria-autocomplete="none" placeholder="Please enter a number sir" name="integer" value="{{$integer}}">
</form>
