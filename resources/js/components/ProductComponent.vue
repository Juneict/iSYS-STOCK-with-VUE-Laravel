<template>
  <div class="container">
    <div class="row justify-content-end mb-3 mt-3">
      <div class="col-3">
        <div class="head-title">
          <h1><i class="fa-solid fa-radiation"></i> iSYS APP</h1>
          <span class="head-span">stock management system</span>
        </div>
      </div>
      <!-- search -->
      <div class="col-3">
        <button type="submit" class="btn backColor" @click="create">
          <i class="fa-solid fa-circle-plus mr-1"></i> Create
        </button>
      </div>
      <div class="col-2" >
        <div class="form-group mb-1">
          
          <select
            class="form-select"
            v-model="brand"
            aria-label="Default select example"
            @change="onChange()"
            
          >
            <option value="">Brand</option>
            <option v-for="brand in brands" :key="brand.id" :value="brand.brand_name">
              {{ brand.brand_name }}
            </option>
          </select>
        </div>
      </div>
      <div class="col-2">
        <select
                  class="form-select"
                  v-model="category"
                  aria-label="Default select example"
                  @change="onSearch()"
                >
                  <option value=""><b>Category</b> </option>
                  <option
                    v-for="category in categories"
                    :key="category.id"
                    :value="category.category_name"
                  >
                    {{ category.category_name }}
                  </option>
          </select>
      </div>
      <div class="col-2">
        <form action="" @submit.prevent="view">
          <div class="input-group">
            <input
              type="text"
              v-model="search"
              placeholder="search"
              class="form-control"
            />
            <div class="input-group-append">
              <button type="submit" class="btn btn-primary backColor">
                <i class="fa-solid fa-magnifying-glass"></i>
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <!-- search end -->

    <div class="row">
      <!-- create -->
      <div class="col-3">
        <div class="card">
          <div class="card-header backColor">
            <h4 class="card-title">
              {{ isEditMode ? "Edit a Product" : "Create a Product" }}
            </h4>
          </div>
          <div class="card-body">
            <AlertError
              :form="product"
              message="There were some problems with your input."
            />
            <form action="" @submit.prevent="isEditMode ? update() : store()">
              <div class="form-group mb-1">
                <label for="">Name</label>
                <input
                  type="text"
                  v-model="product.name"
                  class="form-control"
                  placeholder="enter product name"
                />
                <HasError :form="product" field="name" />
              </div>
              <div class="form-group mb-1">
                <label for="">Brand</label>
                <select
                  class="form-select"
                  v-model="product.brand_id"
                  aria-label="Default select example"
                >
                  <option value="">Select</option>
                  <option
                    v-for="brand in brands"
                    :key="brand.id"
                    :value="brand.id"
                  >
                    {{ brand.brand_name }}
                  </option>
                </select>
              </div>
              <div class="form-group mb-1">
                <label for="">Category</label>
                <select
                  class="form-select"
                  v-model="product.category_id"
                  aria-label="Default select example"
                >
                  <option value="">Select</option>
                  <option
                    v-for="category in categories"
                    :key="category.id"
                    :value="category.id"
                  >
                    {{ category.category_name }}
                  </option>
                </select>
              </div>

              <div class="form-group mb-1">
                <label for="">Price</label>
                <input
                  type="number"
                  v-model="product.sale_price"
                  class="form-control"
                  placeholder="enter product name"
                />
                <HasError :form="product" field="sale_price" />
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
                {{ isEditMode ? "Update" : "Save" }}
              </button>
            </form>
          </div>
        </div>
      </div>
      <!-- createEnd -->

      <!-- data -->
      <div class="col-9">
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Brand</th>
              <th>Category</th>
              <th>Price</th>
              <th>Stock</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="product in products.data" :key="product.id">
              <td>{{ product.id}}</td>
              <td>{{ product.name }}</td>
              <td>{{ product.brand_name }}</td>
              <td>{{ product.category_name }}</td>
              <td>{{ product.sale_price }}</td>
              <td>{{ product.stock }}</td>
              <td>
                <button class="btn btn-success btn-sm" @click="edit(product)">
                  <i class="fa-solid fa-pen-to-square"></i>
                </button>
                <button
                  class="btn btn-danger btn-sm"
                  @click="destroy(product.id)"
                >
                  <i class="fa-solid fa-trash-can"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
        <pagination :data="products" @pagination-change-page="view">
        </pagination>
      </div>
      <!-- data end -->
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Form from "vform";
import {
  Button,
  HasError,
  AlertError,
  AlertErrors,
  AlertSuccess,
} from "vform/src/components/bootstrap5";
Vue.component(Button.name, Button);
Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);
Vue.component(AlertErrors.name, AlertErrors);
Vue.component(AlertSuccess.name, AlertSuccess);

export default {
  name: "ProductComponent",
  data() {
    return {
      isEditMode: false,
      search: "",
      brand: "",
      category:"",
      products: {},
      brands: [],
      categories: [],
      product: new Form({
        id:"",
        name: "",
        brand_id: "",
        category_id: "",
        sale_price: "",
        stock: "",
      }),
    };
  },
  methods: {
    onChange(){
      this.$Progress.start();
      axios
        .get(`/api/products?search=${this.brand}`)
        .then((res) => {
          console.log(this.category);
          this.products = res.data;
          this.$Progress.finish();
        })
        .catch((error) => {
          this.$Progress.fail();
        });
    },
    onSearch(){
      this.$Progress.start();
      axios
        .get(`/api/products?search=${this.category}`)
        .then((res) => {
          console.log(this.category);
          this.products = res.data;
          this.$Progress.finish();
        })
        .catch((error) => {
          this.$Progress.fail();
        });
    },
    getBrand() {
      axios.get("/api/brands").then((res) => {
        this.brands = res.data;
      });
    },
    getCategory() {
      axios.get("/api/categories").then((res) => {
        this.categories = res.data;
      });
    },
    view(page = 1) {
      this.$Progress.start();
      axios
        .get(`/api/products?page=${page}&search=${this.search}`)
        .then((res) => {
          this.products = res.data;
          this.$Progress.finish();
        })
        .catch((error) => {
          this.$Progress.fail();
        });
    },
    create() {
      this.product.clear();
      this.isEditMode = false;
    },
    store() {
      this.product.post("/api/products").then((res) => {
        this.view();
        this.product.reset();
        Toast.fire({
          icon: "success",
          title: "Created successfully",
        });
      });
    },
    edit(product) {
      this.product.clear();
      this.isEditMode = true;
      this.product.fill(product);
    },
    update() {
      console.log(this.product.id)
      this.product.put(`/api/products/${this.product.id}`).then((res) => {
        
        this.view();
        this.product.reset();
        Toast.fire({
          icon: "success",
          title: "Updated successfully",
        });
      })
      .catch((error)=>{
        console.log(error);
      })
    },
    destroy(id) {
      console.log(product);
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
      }).then((result) => {
        
        if (result.isConfirmed) {
          axios.delete(`/api/products/${this.product.id}`).then((res) => this.view());
          Swal.fire("Deleted!", "Your product has been deleted.", "success");
          Toast.fire({
            icon: "success",
            title: "Deleted successfully",
          });
        }
      });
    },
  },
  created() {
    this.view();
    this.getBrand();
    this.getCategory();
  },
};
</script>

<style scoped>
/* div.dropdown-menu.open{
  max-height: 114px !important;
  overflow: hidden;
}
ul.dropdown-menu.inner{
  max-height: 160px !important;
  overflow-y: auto;
} */
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