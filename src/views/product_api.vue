<template>
  <!-- Container หลัก -->
  <div class="container my-5">
    <div class="row">
      <!-- ใช้ v-for เพื่อวนลูปแสดงสินค้าแต่ละรายการ -->
      <div class="col-md-3 mb-4" v-for="product in products" :key="product.PK">
        <div class="card h-100">
          <!-- แสดงรูปสินค้า -->
          <img
            :src="product.images"
            width="100"
            class="card-img-top"
            alt="Product Image"
            style="object-fit: contain; width: 100%; height: 200px"
          />
          <div class="card-body">
            <!-- แสดงชื่อสินค้า -->
            <h5 class="card-title">{{ product.xxx }}</h5>
          </div>
          <div class="card-footer">
            <!-- แสดงราคาสินค้า -->
            <small class="text-muted">Price: ${{ product.xxx }}</small>
            &nbsp;
            <!-- ปุ่มสำหรับเพิ่มสินค้า -->
            <button type="button" class="btn btn-outline-primary">Add</button>
          </div>
        </div>
      </div>
    </div>
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
