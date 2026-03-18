<template>
    <div class="container mt-4">
      <!-- หัวข้อของตาราง -->
      <h2 class="mb-3">product List</h2>

      <!-- ตารางแสดงข้อมูลผู้ใช้ -->
      <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Title</th>
            <th scope="col">Description</th>
            <th scope="col">Category</th>
            <th scope="col">Price</th>
            <th scope="col">Image</th>
    
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in products" :key="item.id">
            <td>{{ item.id}} </td>
            <td>{{ item.title}} </td>
            <td>{{ item.description}} </td>
            <td>{{ item.category}} </td>
            <td>{{ item.price}} </td>
            <td><img
            :src="item.images"
            width="100"
            class="card-img-top"
            alt="Product Image"
            style="object-fit: contain; width: 100%; height: 200px"
          /></td>
         
          </tr>
        </tbody>
      </table>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const products = ref([])
const loading = ref(true)
const error = ref(null)

onMounted(async () => {
  try {
    const res = await fetch('https://dummyjson.com/products')
    const data = await res.json()
    products.value = data.products
  } catch (err) {
    error.value = 'โหลดข้อมูลไม่สำเร็จ'
  } finally {
    loading.value = false
  }
})
</script>
