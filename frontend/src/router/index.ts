import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import Auth from '../views/Auth.vue'
import UserView from '../views/UserView.vue'
import EditView from '../views/EditView.vue'
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/login',
      name: 'Login',
      component: Auth
    },
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: "/user",
      name: 'user',
      component: UserView,
    },
    {
      path: "/edit",
      name: "edit",
      component: EditView
    }
  ]
})
router.beforeEach((to, from, next)=> {
  const publicPages = ["/login"];
  const authRequired = !publicPages.includes(to.path);
  const token = localStorage.getItem('token');
  console.log(token);
  
  if (authRequired && token === "") {
    next('/login');
  } else {
    console.log("next");
    
    next();
  }
})

export default router
