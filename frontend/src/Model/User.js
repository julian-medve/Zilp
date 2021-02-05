import { is } from 'ramda'

export default class User {
  constructor (user = {}) {
    this.id = is(Number, user.id) ? user.id : 0
    this.firstName = is(String, user.firstName) ? user.firstName : ''
    this.lastName = is(String, user.lastName) ? user.lastName : ''
    this.name = this.firstName + ' ' + this.lastName
    this.image = is(String, user.image) ? user.image : ''
    this.role = is(String, user.role) ? user.role : ''
    this.about = is(String, user.about) ? user.about : ''
    this.dob = is(String, user.dob) ? user.dob : ''
    this.isActive = is(Boolean, user.isActive) ? user.isActive : false
    this.isPrivate = is(Boolean, user.isPrivate) ? user.isPrivate : false

    this.email = is(String, user.email) ? user.email : ''
    this.phone = is(String, user.phone) ? user.phone : ''
    this.plateNumber = is(String, user.plateNumber) ? user.plateNumber : ''
    this.balance = is(Number, user.balance) ? user.balance : 0
    this.verifiedDriver = is(String, user.verifiedDriver) ? user.verifiedDriver : ''
  }
}
