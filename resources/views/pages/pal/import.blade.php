@extends('layouts.layout')

@section('title', 'PAL Import')

@section('content')
<h1>Import</h1>
<br>
<br>

<h3>Property Items</h3>
<form action="{{ url('/import/property_import_file') }}" method="post" enctype="multipart/form-data">
  <input type="file" name="myfile">
  <button type="submit">Submit</button>
</form>
<br>
<br>

<h3>Major Article Items</h3>
<form action="{{ url('/import/major_import_file') }}" method="post" enctype="multipart/form-data">
  <input type="file" name="myfile">
  <button type="submit">Submit</button>
</form>
<br>
<br>

<h3>Main Article Items</h3>
<form action="{{ url('/import/main_import_file') }}" method="post" enctype="multipart/form-data">
  <input type="file" name="myfile">
  <button type="submit">Submit</button>
</form>
<br>
<br>

<h3>Stock Items</h3>
<form action="{{ url('/import/stock_import_file') }}" method="post" enctype="multipart/form-data">
  <input type="file" name="myfile">
  <button type="submit">Submit</button>
</form>
<br>
<br>

<h3>Suppliers</h3>
<form action="{{ url('/import/supplier_import_file') }}" method="post" enctype="multipart/form-data">
  <input type="file" name="myfile">
  <button type="submit">Submit</button>
</form>
<br>
<br>
@endsection
