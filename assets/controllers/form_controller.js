import {Controller} from '@hotwired/stimulus';
import axios from 'axios';

export default class extends Controller {
    static targets = ['form', 'messages'];

    async submit(event) {
        event.preventDefault();
        const formData = new FormData(this.formTarget);
        try {
            const response = await axios.post(this.formTarget.action, formData);
            this.messagesTarget.innerHTML = response.data.messages;
            if (response.data.success) {
                this.formTarget.reset();
            }
        } catch (error) {
            console.error('An error occurred:', error);
        }
    }
}