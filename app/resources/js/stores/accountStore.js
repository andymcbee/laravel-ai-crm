import {defineStore} from "pinia";

export const useAccountStore = defineStore('account', {
    state: () => ({
        activeAccount: null
    }),
    actions: {
        setActiveAccount(account){
            this.setActiveAccount = account;
        }
    }
})
