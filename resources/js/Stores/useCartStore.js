import { defineStore } from "pinia";

export const useCartStore = defineStore('cart', {
    state: () => ({
        items: JSON.parse(localStorage.getItem('cartItems')) || [],
    }),
    getters: {
        totalItems() {
            return this.items.length;
        },
        totalPrice() {
            return this.items.reduce((total, item) => total + item.price, 0);
        }
    },
    actions: {
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
            // retrieve local storage and use that to check
            return this.items.some(i => i.id === item.id);
        },
        saveToLocalStorage() {
            localStorage.setItem('cartItems', JSON.stringify(this.items));
        }
    },
});
