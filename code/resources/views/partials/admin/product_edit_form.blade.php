<div class="mb-5">
  <div class="d-flex justify-content-between mb-2">
    <h4>Edit product</h4>
  </div>

  <form class = "row g-3" method = "POST" action= {{url('admin/products/edit/'.$entries[0]->id)}}>
    @csrf

    <div class="col-md-8">
      <label for="name" class="form-label">
        <span>Product Name</span> 
        <small class = "required-input">*</small>
      </label>
      <input type="text" class="form-control" name="name" id="name" placeholder="{{$entries[0]->name}}" required>
    </div>

    <div class="col-md-4">
      <label for="price" class="form-label">
        <span>Price</span> 
        <small class = "required-input">*</small>
      </label>
      <input type="text" class="form-control" name="price" id="price" placeholder="{{$entries[0]->price}}" required>
    </div>

    <div class="col-md-4">
      <label for="size" class="form-label">
        <span>Size</span> 
      </label>
      <input type="text" class="form-control" name="size" id="size" placeholder = "{{$entries[0]->size}}">
    </div>
    
    <div class="col-md-4">
      <label for="stock" class="form-label">
        <span>Stock</span> 
        <small class = "required-input">*</small>
      </label>
      <input type="text" class="form-control" name="stock" id="stock" placeholder = "{{$entries[0]->stock}}" required>
    </div>

    <div class="col-md-4">
      <label for="category" class="form-label">
        <span>Category</span> 
        <small class = "required-input">*</small>
      </label>
      <select name="category" id="category" disabled>
        <option value="CPU">CPU</option>
        <option value="GPU">GPU</option>
        <option value="Cooler">Cooler</option>
        <option value="Motherboard">Motherboard</option>
        <option value="PowerSupply">Power Supply</option>
        <option value="Storage">Storage</option>
        <option value="PcCase">Desktop Case</option>
        <option value="Other">Other</option>
      </select>
    </div>

    <div class="col-md-2">
      <label for="brand" class="form-label">
        <span>Brand</span>
        <small class = "required-input">*</small>
      </label>
      <input type="text" class="form-control" name="brand" id="brand" placeholder = "{{$entries[0]->brand}}" required>
    </div>
  
    <div class="col-md-2">
      <label for="field1" class="form-label">
        <span>Specific Attribute 1</span>
      </label>
      <input type="text" class="form-control" name="field1" id="field1" placeholder = "Specification 1">
    </div>

    <div class="col-md-2">
      <label for="field2" class="form-label">
        <span>Specific Attribute 2</span>
      </label>
      <input type="text" class="form-control" name="field2" id="field2" placeholder = "Specification 2">
    </div>

    <div class="col-md-2">
      <label for="field3" class="form-label">
        <span>Specific Attribute 3</span>
      </label>
      <input type="text" class="form-control" name="field3" id="field3" placeholder = "Specification 3">
    </div>

    <div class="col-md-2">
      <label for="field4" class="form-label">
        <span>Specific Attribute 4</span>
      </label>
      <input type="text" class="form-control" name="field4" id="field4" placeholder = "Specification 4">
    </div>

    <div class="col-md-2">
      <label for="field5" class="form-label">
        <span>Specific Attribute 5</span>
      </label>
      <input type="text" class="form-control" name="field5" id="field5" placeholder = "Specification 5">
    </div>

    <div class="col-md-12">
      <label for="description">
        <span>Description</span>
        <small class = "required-input">*</small>
      </label>
      <textarea id="description" name="description" placeholder="{{$entries[0]->description}}" style="height:100px" required></textarea>
    </div>

    <div class="col-12">
      <button class = "btn btn-outline-success" type = "submit">
        Submit
      </button>
    </div>
    
    <table class="tg">
    <thead>
      <tr>
        <th class="tg-ev0v">Category</th>
        <th class="tg-ev0v">CPU</th>
        <th class="tg-ev0v">GPU</th>
        <th class="tg-ev0v">Motherboard</th>
        <th class="tg-ev0v">Storage</th>
        <th class="tg-ev0v">PcCase</th>
        <th class="tg-ev0v">Cooler</th>
        <th class="tg-ev0v">PowerSupply</th>
        <th class="tg-ev0v">Other</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="tg-ev0v">Field 1</td>
        <td class="tg-0pky">baseFreq{float}</td>
        <td class="tg-0pky">memory{number}</td>
        <td class="tg-0pky">chipset{text}</td>
        <td class="tg-0pky">type{text}</td>
        <td class="tg-0pky">type{text}</td>
        <td class="tg-0pky">type{text}</td>
        <td class="tg-0pky">wattage{number}</td>
        <td class="tg-0pky"></td>
      </tr>
      <tr>
        <td class="tg-ev0v">Field 2</td>
        <td class="tg-0pky">turboFreq{float}</td>
        <td class="tg-0pky">coreClock{number}</td>
        <td class="tg-0pky">type{text}</td>
        <td class="tg-0pky">capacity{number}</td>
        <td class="tg-0pky">weight{text}</td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky">certification{text}</td>
        <td class="tg-0pky"></td>
      </tr>
      <tr>
        <td class="tg-ev0v">Field3</td>
        <td class="tg-0pky">socket{text}</td>
        <td class="tg-0pky">boostClock{number}</td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky">color{text}</td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky">type{text}</td>
        <td class="tg-0pky"></td>
      </tr>
      <tr>
        <td class="tg-ev0v">Field4</td>
        <td class="tg-0pky">threads{number}</td>
        <td class="tg-0pky">hdmiPorts{number}</td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
      </tr>
      <tr>
        <td class="tg-ev0v">Field5</td>
        <td class="tg-0pky">cores{number}</td>
        <td class="tg-0pky">displayPorts{number}</td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
      </tr>
    </tbody>
    </table>


    @foreach($errors as $error)
      <div class="col-12">
        <div class = "alert alert-danger">
          <i class="fa fa-times"></i>
          {{$error}}
        </div>
      </div>
    @endforeach
  </form>
</div>