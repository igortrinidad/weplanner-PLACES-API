<style media="screen">
.search-container {
    max-width: 767px;
    margin: 0 auto;
}

.input-group {
    position: relative;
    width: 100%;
}
.input-search {
    position: relative;
    z-index: 1;
    padding: 20px 25px !important;
}

.form-control:focus {
    outline: none !important;
    border-color: #ccc !important;
    box-shadow: none !important;
}

.btn-search {
    position: absolute;
    top: 0; right: -2px;
    border-radius: 0px 4px 4px 0px;
    z-index: 10;
    text-transform: none;
}
</style>
<div class="search-container">
    <div class="input-group">
        <input type="text" class="form-control input-search" placeholder="Pesquise por cidade">
        <button class="btn btn-info btn-search">Buscar</button>
    </div>
</div>
