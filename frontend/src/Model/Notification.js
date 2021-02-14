import { is } from 'ramda'

export default class Notification {
  constructor (notification = {}) {
    this.id = is(String, notification.id) ? notification.id : ''
    this.text = is(String, notification.text) ? notification.text : ''
    this.timeAgo = is(String, notification.timeAgo) ? notification.timeAgo : ''
    this.userId = is(Number, notification.userId) ? notification.userId : ''
    this.me = is(Boolean, notification.me) ? notification.me : false
    this.created_at = is(String, notification.created_at) ? notification.created_at : ''
    this.type = is(String, notification.type) ? notification.type : ''
  }
}
