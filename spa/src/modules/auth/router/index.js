import AuthLayout from '@/modules/user/layouts/AuthLayout.vue';
export default {
    name: 'users',
    path: '/users',
    component: AuthLayout,
    children:[
    {
        path: 'auth',
        name: 'auth',
        component: () => import(/* */ '@/modules/')
    },
]
}