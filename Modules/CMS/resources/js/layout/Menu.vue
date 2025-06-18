<template>
    <ul class="layout-menu">
        <template v-for="(item, i) in filteredMenu" :key="item">
            <menu-item v-if="!item.separator" :item="item" :index="i"></menu-item>
            <li v-if="item.separator" class="menu-separator"></li>
        </template>
    </ul>
</template>

<style lang="scss" scoped></style>

<script setup>
import {ref, computed} from 'vue';
import MenuItem from './MenuItem.vue';
import {useAuth} from './composables/useAuth';

const {isAdmin} = useAuth();

const menuItems = ref([
    {
        label: 'Home',
        items: [{
            label: 'Dashboard',
            icon: 'pi pi-fw pi-home',
            routeName: 'cms.dashboard',
            activeRouteNames: ['cms.dashboard'],
            roles: [0, 1]
        }],
        roles: [0, 1]
    },
    {
        label: 'Services',
        items: [
            {
                label: 'Product',
                icon: 'pi pi-fw pi-shopping-bag',
                routeName: 'products.index',
                activeRouteNames: ['products.index', 'products.create', 'products.edit'],
                roles: [0]
            },
            {
                label: 'Pricing',
                icon: 'pi pi-fw pi-dollar',
                routeName: 'pricing.index',
                activeRouteNames: ['pricing.index'],
                roles: [0, 1]
            },
            {
                label: 'Order',
                icon: 'pi pi-fw pi-shopping-cart',
                class: 'rotated-icon',
                activeRouteNames: ['subscriber-histories.index'],
                items: [
                    {
                        label: 'List Order',
                        icon: 'pi pi-fw pi-wallet',
                        routeName: 'subscriber-histories.index'
                    },
                ],
                roles: [0, 1]
            },
        ],
        roles: [0, 1]
    },
    {
        label: 'Notice',
        items: [
            {
                label: 'Setting Notice',
                icon: 'pi pi-fw pi-bell',
                routeName: 'settings.edit',
                activeRouteNames: ['settings.edit'],
                roles: [0]
            }
        ],
        roles: [0]
    },
    {
        label: 'Shop',
        items: [
            {
                label: 'List Shop',
                icon: 'pi pi-fw pi-shop',
                routeName: 'shops.index',
                activeRouteNames: ['shops.index'],
                roles: [0]
            },
            {
                label: 'Add Shop',
                icon: 'pi pi-fw pi-plus',
                routeName: 'shops.create',
                activeRouteNames: ['shops.create'],
                roles: [0]
            },
            {
                label: 'Shop Detail',
                icon: 'pi pi-fw pi-shop',
                routeName: 'shops.key.edit',
                activeRouteNames: ['shops.key.edit'],
                roles: [1]
            }
        ],
        roles: [0, 1]
    },
    // {
    //     label: 'Manage',
    //     icon: 'pi pi-fw pi-briefcase',
    //     items: [
    //         {
    //             label: 'Account',
    //             icon: 'pi pi-fw pi-user',
    //             routeName: 'accounts.index',
    //             roles: [0, 1]
    //         },
    //         {
    //             label: 'Crud',
    //             icon: 'pi pi-fw pi-pencil',
    //             routeName: 'crud.index',
    //             roles: [0, 1]
    //         }
    //     ],
    //     roles: [0, 1]
    // }
]);

const filteredMenu = computed(() => {
    return menuItems.value.map(section => {
        if (!section.roles?.includes(isAdmin.value ? 0 : 1)) {
            return null;
        }

        const filteredSection = {...section};

        if (filteredSection.items) {
            filteredSection.items = filteredSection.items.filter(item =>
                item.roles?.includes(isAdmin.value ? 0 : 1)
            );
        }

        if (filteredSection.items?.length === 0) {
            return null;
        }

        return filteredSection;
    }).filter(Boolean);
});
</script>
