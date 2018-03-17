@extends('layouts.app')

<style>
/*table, th, td {
    border: 1px solid black;
}*/
/*.navbar>.container, .navbar>.container-fluid {
            display: initial !important;
        }*/
.modal {
}
.vertical-alignment-helper {
    display:table;
    height: 100%;
    width: 100%;
}
.vertical-align-center {
    /* To center vertically */
    display: table-cell;
    vertical-align: middle;
}
.modal-content {
    /* Bootstrap sets the size of the modal in the modal-dialog class, we need to inherit it */
    width:inherit;
    height:inherit;
    /* To center horizontally */
    margin: 0 auto;
}
</style>
@section('content')

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="vertical-alignment-helper">
        <div class="modal-dialog vertical-align-center">
            <div class="modal-content">
                <div class="modal-header">
                     <h4 class="modal-title" id="myModalLabel">Similar Books</h4>
                </div>

                <div class="modal-body"> 
                    <ul>
                    @for($i = 0; $i < count($similar); $i++)
                    <ul>
                        <li>ISBN: {{$similar[$i]->ISBN}} </li>
                        <li>Title: {{$similar[$i]->Title}} </li>
                        <li>Title: {{$similar[$i]->Author}} </li>
                        <br />
                    </ul>
                    @endfor
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
        {{-- {{print_r($similar);}} --}}
        <div class="col-sm-offset-2 col-sm-8">

            <div class="panel panel-default">
                <div class="panel-heading">
                    Search Criteria
                </div>

                <div class="panel-body">
                    <div class="form-horizontal">
                        
                        {{-- ISBN, Title, Author, Publication Year, Year Of Publication, Publisher --}}

                        <!-- Task Name -->
                        <div class="form-group">
                            <label for="task-name" class="col-sm-2 control-label">ISBN</label>
                            <div class="col-sm-8">
                                <input type="text" name="name" id="ISBN" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="task-name" class="col-sm-2 control-label">Title</label>
                            <div class="col-sm-8">
                                <input type="text" name="name" id="Title" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="task-name" class="col-sm-2 control-label">Author</label>
                            <div class="col-sm-8">
                                <input type="text" name="name" id="Author" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="task-name" class="col-sm-2 control-label">Publication Year</label>
                            <div class="col-sm-8">
                                <input type="text" name="name" id="PublicationYear" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="task-name" class="col-sm-2 control-label">Publisher</label>
                            <div class="col-sm-8">
                                <input type="text" name="name" id="Publisher" class="form-control">
                            </div>
                        </div>
                        <!-- Add Task Button -->
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-6">
                                <button id ='search' type="submit" class="btn btn-default">
                                    <i class="fa fa-btn fa-plus"></i>Search
                                </button>
                            </div>
                        </div>
                    <div>
                </div>
            </div>
        </div>
    </div>
    <div id="table-container" hidden>
        <table id="page-content" class="col-sm-12">
            <thead>
                <tr>
                   <th class="col-sm-1">ISBN</th>
                   <th class="col-sm-2">Title</th>
                   <th class="col-sm-1">Author</th>
                   <th class="col-sm-2">Publication Year</th>
                   <th class="col-sm-2">Publisher</th>
                   <th class="col-sm-4">Image</th>
                   <th class="col-sm-2">Rating/10</th>
                   <th class="col-sm-1">Add To List</th>
                   <th class="col-sm-1">Similar Books</th>
               </tr>
            </thead>

        </table>
        <div class="row">
          <div id="paginationContainer" class="col-sm-12">
            <ul id="paginate" class="pagination-sm"></ul>
          </div>
        </div>
    </div>
@endsection


{{-- <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.16/r-2.2.0/rr-1.2.3/sc-1.4.3/datatables.min.js"></script> --}}
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css"/>

<script type="text/javascript">

$(document).ready(function() {

    var pages;

    var table = $('#page-content').DataTable();

    $('#search').click(function(){
        $.post('{{URL::to('/searchBooks')}}', {ISBN : $("#ISBN").val() , Title: $("#Title").val() , Author: $("#Author").val(), PublicationYear: $("#PublicationYear").val() , Publisher: $("#Publisher").val() }, function(data){
        console.log(data);
        $("#page-content").show();
        $('#pagination-demo').remove();
        $('#paginationContainer').html("<div class='pagination-sm' id='paginate'></div>");
        table.clear();
        for(var i = 0; i < data.length; i++){
            index = data[i];
            if(index['Rating'] == null){
                index['Rating'] =  'None';
            }
            table.row.add( [
                "<div id='ISBN_"+index['ISBN'] + "'>" + index['ISBN']  + "</div>",
                "<div id='BookTitle_"+index['ISBN'] + "'>" + index['BookTitle']  + "</div>",
                "<div id='BookAuthor_"+index['ISBN'] + "'>" + index['BookAuthor']  + "</div>",
                "<div id='YearOfPublication_"+index['ISBN'] + "'>" + index['YearOfPublication']  + "</div>",
                "<div id='Publisher_"+index['ISBN'] + "'>" + index['Publisher']  + "</div>",
                "<div id='Image_"+index['ISBN'] + "'>" + "<img src='" + index['ImageURLM'] + "'>"  + "</div>",
                "<div id='Rating_"+index['ISBN'] + "'>" + index['Rating']  + "</div>",
                "<button type='button' class='btn btn-info addToListBtn' data-isbn = '" + index['ISBN'] + "'>Add</button>" ,
                "<button type='button' data-toggle='modal' data-target='#myModal'button class='btn btn-info similarToListBtn' data-isbn = '" + index['ISBN'] + "'>List</button>",
                ]);
        }
        $("#table-container").removeAttr('hidden');

        table.draw();
        });
    });

    $(document).on('click', '.addToListBtn', function () {
        $.post('{{URL::to('/addBook')}}', {ISBN : $(this).data('isbn') }, function(data){
            console.log(data);
        }); 
        $(this).text("Added :)")
    });



} );


</script>