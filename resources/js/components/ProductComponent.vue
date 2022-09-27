<template>
  <div class="container">
    <div class="row justify-content-end mb-3 mt-3">
      <div class="col-3">
        <div class="head-title">
          <h1><i class="fa-solid fa-radiation"></i> iSYS APP</h1>
          <span class="head-span">stock management system</span>
        </div>
      </div>
      <div class="col-2">
        <button type="submit" class="btn backColor" @click="create">
          <i class="fa-solid fa-circle-plus mr-1"></i> Create
        </button>
      </div>
      <div class="col-1">
        <select
          class="form-control form-select"
          aria-label="Default select example"
        >
          <option selected>Model</option>
          <option value="1">One</option>
          <option value="2">Two</option>
          <option value="3">Three</option>
        </select>
      </div>
      <div class="col-2">
        <select
          class="form-control form-select"
          aria-label="Default select example"
        >
          <option selected>Brand</option>
          <option value="1">One</option>
          <option value="2">Two</option>
          <option value="3">Three</option>
        </select>
      </div>
      <div class="col-2">
        <select
          class="form-control form-select"
          aria-label="Default select example"
        >
          <option selected>Category</option>
          <option value="1">One</option>
          <option value="2">Two</option>
          <option value="3">Three</option>
        </select>
      </div>
      <div class="col-2">
        <form action="" @submit.prevent="view">
          <div class="input-group">
            <input type="text" v-model="search" placeholder="search" class="form-control" />
            <div class="input-group-append">
              <button type="submit" class="btn btn-primary backColor">
                <i class="fa-solid fa-magnifying-glass"></i>
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <div class="row">
      <div class="col-3">
        <div class="card">
          <div class="card-header backColor">
            <h4 class="card-title">
              {{ isEditMode ? "Edit a Product" : "Create a Product" }}
            </h4>
          </div>
          <div class="card-body">
            <form action="" @submit.prevent="isEditMode ? update() : store()">
              <div class="form-group mb-1">
                <label for="">Name</label>
                <input
                  type="text"
                  v-model="product.name"
                  class="form-control"
                  placeholder="enter product name"
                />
              </div>
              <div class="form-group mb-1">
                <label for="">Brand</label>
                <input
                  type="text"
                  v-model="product.brand"
                  class="form-control"
                  placeholder="enter product brand"
                />
              </div>
              <div class="form-group mb-1">
                <label for="">Model</label>
                <input
                  type="text"
                  v-model="product.model"
                  class="form-control"
                  placeholder="enter product model"
                />
              </div>
              <div class="form-group mb-1">
                <label for="">Category</label>
                <input
                  type="text"
                  v-model="product.category"
                  class="form-control"
                  placeholder="enter product category"
                />
              </div>
              <div class="form-group mb-1">
                <label for="">Price</label>
                <input
                  type="number"
                  v-model="product.sale_price"
                  class="form-control"
                  placeholder="enter product name"
                />
              </div>
              <div class="form-group mb-1">
                <label for="">Stock</label>
                <input
                  type="number"
                  v-model="product.stock"
                  class="form-control"
                  placeholder="enter product stock"
                />
              </div>
              <button type="submit" class="btn btn-block mt-3 backColor">
                <i class="fa-solid fa-floppy-disk mr-1"></i>
                {{ isEditMode ? "Update" : "Save"}}
              </button>
            </form>
          </div>
        </div>
      </div>
      <div class="col-9">
        <table class="table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Brand</th>
              <th>Model</th>
              <th>Category</th>
              <th>Price</th>
              <th>Stock</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="product in products.data" :key="product.id">
              <td>{{ product.name }}</td>
              <td>{{ product.brand }}</td>
              <td>{{ product.model }}</td>
              <td>{{ product.category }}</td>
              <td>{{ product.sale_price }}</td>
              <td>{{ product.stock }}</td>
              <td>
                <button class="btn btn-success btn-sm" @click="edit(product)">
                  <i class="fa-solid fa-pen-to-square"></i>
                </button>
                <button class="btn btn-danger btn-sm" @click="destroy(product.id)">
                  <i class="fa-solid fa-trash-can"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
        <pagination :data="products"  @pagination-change-page="view"> </pagination>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "ProductComponent",
  data() {
    return {
      isEditMode: false,
      search:'',
      products: {},
      product: {
        id:"",
        name: "",
        brand: "",
        model: "",
        category: "",
        sale_price: "",
        stock: "",
      },
    };
  },
  methods: {
    view(page=1) {
      axios.get(`/api/products?page=${page}&search=${this.search}`).then((res) => {
        this.products = res.data;
      });
    },
    create() {
      this.isEditMode = false;
      this.product.name = "";
      this.product.brand = "";
      this.product.model = "";
      this.product.category = "";
      this.product.sale_price = "";
      this.product.stock = "";
    },
    store() {
      axios.post("/api/products", this.product).then((res) => {
        this.view();
        this.product = {
          id: "",
          name: "",
          brand: "",
          model: "",
          category: "",
          sale_price: "",
          stock: "",
        };
      });
    },
    edit(product) {
      this.isEditMode = true;
      this.product.id = product.id;
      this.product.name = product.name;
      this.product.brand = product.brand;
      this.product.model = product.model;
      this.product.category = product.category;
      this.product.sale_price = product.sale_price;
      this.product.stock = product.stock;
      console.log(product.id);
    },
    update() {
      axios
        .put(`/api/products/${this.product.id}`, this.product)
        .then((res) => {
          this.view();
          this.product.name = "";
          this.product.brand = "";
          this.product.model = "";
          this.product.category = "";
          this.product.sale_price = "";
          this.product.stock = "";
        });
    },
    destroy(id){
      if(!confirm('Are you sure you want to delete')){
        return
      }
      axios.delete(`/api/products/${id}`)
      .then(res=> this.view());
    }
  },
  created() {
    this.view();
  },
};
</script>

<style scoped>
.head-title {
  color: #245565;
  font-weight: bold;
}
.head-span {
  font-size: 14px;
}
/* h1{
    color: #245565;
    font-weight: bold;
} */
.backColor {
  background-color: #245565;
  color: #fff;
}
</style>