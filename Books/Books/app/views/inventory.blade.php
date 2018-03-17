@extends('layouts.app')

<style>
.book-Bar {
    float: left;
    height: 50px;
    padding: 15px 15px;
    font-size: 16px;
    line-height: 20px;
}
.blockStyle {
    display:block;
}
</style>
@section('content')

{{-- {{print_r($books)}} --}}

<table id="example" class="display" cellspacing="0">
        <thead>
            <tr>
                <th>ISBN</th>
                <th>Book</th>
                <th>Author</th>
                <th>Date Added</th>
                <th>Rating</th>
                <th>Similar Books</th>
                <th>Image</th>
                <th>Remove</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>ISBN</th>
                <th>Book</th>
                <th>Author</th>
                <th>Date Added</th>
                <th>Rating</th>
                <th>Similar Books</th>
                <th>Image</th>
            </tr>
        </tfoot>
        @for($i = 0; $i < sizeof($books); $i++)
        	<tr>
        		<td>{{$books[$i]->ISBN}}</td>
        		<td>{{$books[$i]->Title}}</td>
        		<td>{{$books[$i]->Author}}</td>
        		<td> <?php echo date("j M y",$books[$i]->Time); ?></td>
        		<td>
        			@if ($books[$i]->Rating == null)
        				None
        			@else
        				{{$books[$i]->Rating}}
        			@endif
        		</td>
        		<td><button type="button" class="btn btn-default listBtn" data-isbn="{{$books[$i]->ISBN}}">List</button></td>
        		<td><img class='blockStyle' src='{{$books[$i]->Image}}'></td>
                <td><button type="button" class="btn btn-default removeBtn" data-isbn="{{$books[$i]->ISBN}}">Remove</button></td>
        	</tr>
        @endfor
</table>


@endsection

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css"/>

<script type="text/javascript">
$(document).ready(function() {
    var table = $('#example').DataTable();

    $(".removeBtn").on('click', function(){
        $.post('{{URL::to('/inventory')}}', {ISBN : $(this).data('isbn'), type: 'remove' }, function(data){
            console.log(data);
        }); 
    table.row ($(this).parents('tr').remove().draw());
    });





} );
</script>
