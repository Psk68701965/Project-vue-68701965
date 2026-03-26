<template>

  <div >

  <nav class="navbar navbar-expand-lg " style="background-color: #608ef1;">
  <div class="container">
    <a class="navbar-brand" href="/">Shop DD</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="#navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="/">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/product">Product</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/product_list">Product List</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/gold">Gold</a>
        </li>

         <li class="nav-item dropdown" :class="{ show: openCustomersDropdown }">
          <a class="nav-link dropdown-toggle" href="#" role="button" @click.prevent="toggleCustomersDropdown" :aria-expanded="openCustomersDropdown">
            Customers
          </a>
          <ul class="dropdown-menu" :class="{ show: openCustomersDropdown }">
            <li><router-link class="dropdown-item" to="/customershow" @click="closeDropdowns">Show customers</router-link></li>
            <li><router-link class="dropdown-item" to="/employeeshow" @click="closeDropdowns">Show employees</router-link></li>
            <li><hr class="dropdown-divider"></li>
            <li><router-link class="dropdown-item" to="/add_employee" @click="closeDropdowns">Add Employee</router-link></li>
          </ul>
        </li>


        <li class="nav-item dropdown" :class="{ show: openRegisterDropdown }">
          <a class="nav-link dropdown-toggle" href="#" role="button" @click.prevent="toggleRegisterDropdown" :aria-expanded="openRegisterDropdown">
            Register
          </a>
          <ul class="dropdown-menu" :class="{ show: openRegisterDropdown }">
            <li><router-link class="dropdown-item" to="/register" @click="closeDropdowns">Register</router-link></li>
            <li><a class="dropdown-item" href="#">Login</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Logout</a></li>
          </ul>
        </li>

        <li class="nav-item">
           <a class="nav-link" href="/about">About</a>
        </li>
  
        <li class="nav-item">
           <a class="nav-link" href="/contact">Contact</a>
        </li>

      </ul>
      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search"/>
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>


<!--เปิดหน้าเพจตามที่คลิกเลือกเมนู -->
  <router-view/>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const openCustomersDropdown = ref(false)
const openRegisterDropdown = ref(false)

function toggleCustomersDropdown() {
  openCustomersDropdown.value = !openCustomersDropdown.value
  openRegisterDropdown.value = false
}

function toggleRegisterDropdown() {
  openRegisterDropdown.value = !openRegisterDropdown.value
  openCustomersDropdown.value = false
}

function closeDropdowns() {
  openCustomersDropdown.value = false
  openRegisterDropdown.value = false
}

onMounted(() => {
  document.addEventListener('click', (event) => {
    const nav = document.querySelector('.navbar')
    if (!nav.contains(event.target)) {
      closeDropdowns()
    }
  })
})
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  /* text-align: center; */
  color: #2c3e50;
}

nav {
  padding: 30px;
}

nav a {
  font-weight: bold;
  color: #2c3e50;
}

nav a.router-link-exact-active {
  color: #42b983;
}
</style>
