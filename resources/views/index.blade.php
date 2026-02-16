@extends('layouts.app')

@section('title', 'Escaner IA Inventario')

@section('content')

<div class="container-fluid">

    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Escanear artículo</h3>
        </div>

        <div class="card-body">

            {{-- Selector --}}
            <div class="mb-6">
                <input type="file"
                       id="imageInput"
                       class="form-control"
                       accept="image/*"
                       capture="environment">
                <small class="text-muted">
                    En móvil se abrirá la cámara automáticamente.
                </small>
            </div>

            {{-- Preview --}}
            <div class="mb-6 text-center">
                <img id="previewImage"
                     class="img-fluid rounded d-none shadow"
                     style="max-height:300px;">
            </div>

            {{-- Loader --}}
            <div id="loadingBox" class="text-center d-none py-5">
                <div class="spinner-border text-primary"></div>
                <div class="mt-3 fw-semibold">
                    Analizando con IA...
                </div>
            </div>

            {{-- Resultado --}}
            <div id="resultBox" class="d-none">

                <div class="mb-4">
                    <label class="form-label">Título</label>
                    <input type="text" id="title" class="form-control form-control-solid">
                </div>

                <div class="mb-4">
                    <label class="form-label">Categoría</label>
                    <input type="text" id="category" class="form-control form-control-solid">
                </div>

                <div class="mb-4">
                    <label class="form-label">Descripción</label>
                    <textarea id="description"
                              class="form-control form-control-solid"
                              rows="3"></textarea>
                </div>

                <div class="d-grid">
                    <button class="btn btn-primary">
                        Confirmar y Guardar
                    </button>
                </div>

            </div>

        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>

const input = document.getElementById('imageInput');

input.addEventListener('change', function(e){

    const file = e.target.files[0];
    if(!file) return;

    previewImage(file);
    analyzeImage(file);
});

function previewImage(file){

    const reader = new FileReader();

    reader.onload = function(e){
        const img = document.getElementById('previewImage');
        img.src = e.target.result;
        img.classList.remove('d-none');
    };

    reader.readAsDataURL(file);
}

function analyzeImage(file){

    let formData = new FormData();
    formData.append('image', file);

    document.getElementById('loadingBox').classList.remove('d-none');
    document.getElementById('resultBox').classList.add('d-none');

    fetch("{{ route('inventory.ai.scan') }}", {
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {

        document.getElementById('loadingBox').classList.add('d-none');

        if(data.error){
            alert("Error: " + data.error);
            return;
        }

        document.getElementById('resultBox').classList.remove('d-none');

        document.getElementById('title').value = data.title ?? '';
        document.getElementById('category').value = data.category ?? '';
        document.getElementById('description').value = data.description ?? '';

    })
    .catch(err => {
        document.getElementById('loadingBox').classList.add('d-none');
        alert("Error procesando imagen");
    });
}

</script>
@endpush
