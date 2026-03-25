import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView
  },

  {
    path: '/about',
    name: 'about',
    component: () => import( '../views/AboutView.vue')
  },
  {
    path: '/contact',
    name: 'contact',
    component: () => import( '../views/Contact.vue')
  },
  {
    path: '/product',
    name: 'product',
    component: () => import( '../views/product_api.vue')
  },
  {
    path: '/product_list',
    name: 'product_list',
    component: () => import( '../views/productlist_api.vue')
  },
  {
    path: '/gold',
    name: 'gold',
    component: () => import( '../views/gold.vue')
  },
  {
    path: '/customershow',
    name: 'customershow',
    component: () => import( '../views/show_customers.vue')
  },
  {
    path: '/employeeshow',
    name: 'employeeshow',
    component: () => import( '../views/show_employees.vue')
  },
  {
    path: '/register',
    name: 'register',
    component: () => import( '../views/register.vue')
  }


]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
