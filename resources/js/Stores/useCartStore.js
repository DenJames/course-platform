import { defineStore } from "pinia";
import { usePage } from '@inertiajs/vue3';

export const useCartStore = defineStore('cart', {
    state: () => {
        const user = usePage().props.auth?.user;
        const storageKey = user ? `cartItems_${user.id}` : 'cartItems';
        const savedItems = localStorage.getItem(storageKey);

        return {
            items: savedItems ? JSON.parse(savedItems) : [],
        };
    },
    getters: {
        totalItems() {
            return this.items.length;
        },
        totalPrice() {
            return this.items.reduce((total, item) => total + item.price, 0);
        }
    },
    actions: {
        getStorageKey() {
            const user = usePage().props.auth?.user;
            return user ? `cartItems_${user.id}` : 'cartItems';
        },
        reinitializeCart() {
            const storageKey = this.getStorageKey();
            const savedItems = localStorage.getItem(storageKey);
            this.items = savedItems ? JSON.parse(savedItems) : [];
        },
        addItem(item) {
            this.items.push(item);
            this.saveToLocalStorage();
        },
        removeItem(item) {
            const index = this.items.findIndex(i => i.id === item.id);
            this.items.splice(index, 1);
            this.saveToLocalStorage();
        },
        clearCart() {
            this.items = [];
            this.saveToLocalStorage();
        },
        isInCart(item) {
            return this.items.some(i => i.id === item.id);
        },
        saveToLocalStorage() {
            const storageKey = this.getStorageKey();
            localStorage.setItem(storageKey, JSON.stringify(this.items));
        }
    },
});
