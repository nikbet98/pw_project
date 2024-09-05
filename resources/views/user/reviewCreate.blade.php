@extends('layout.master') 

@section('body')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Scrivi una recensione</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('product.review.store', $product->id) }}"> 
                        @csrf

                        <div class="form-group">
                            <label for="title">Titolo</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>

                        <div class="form-group">
                            <label for="text">Testo</label>
                            <textarea class="form-control" id="text" name="text" rows="5" required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="stars">Valutazione</label>
                            <select class="form-control" id="stars" name="stars" required>
                                <option value=1>1 stella</option>
                                <option value=2>2 stelle</option>
                                <option value=3>3 stelle</option>
                                <option value=4>4 stelle</option>
                                <option value=5>5 stelle</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Invia recensione</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
