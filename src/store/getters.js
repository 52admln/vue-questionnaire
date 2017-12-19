export const naire = state => state.naire
export const status = state => state.status
export const isAdmin = state => state.isAdmin
export const getUser = state => sessionStorage.getItem('USER_NAME')
export const getToken = state => sessionStorage.getItem('JWT_TOKEN')

