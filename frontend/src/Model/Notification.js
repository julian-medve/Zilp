import { is } from 'ramda'

export default class Notification {
  constructor (notification = {}) {
    this.id = is(Number, notification.id) ? notification.id : 0
    this.text = is(String, notification.text) ? notification.text : ''
    this.timeAgo = is(String, notification.timeAgo) ? notification.timeAgo : ''
    this.userId = is(Number, notification.userId) ? notification.userId : ''
    this.me = is(Boolean, notification.me) ? notification.me : false
    this.created_at = is(Boolean, notification.created_at) ? notification.created_at : false
  }
}
