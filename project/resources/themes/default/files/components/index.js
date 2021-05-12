import Vue from 'vue'
import Card from './Card'
import Icon from './Icon'
import Child from './Child'
import Button from './Button'
import FileUpload from './FileUpload'
import MoneyInput from './MoneyInput'
import TwoFaVerifyForm from './TwoFaVerifyForm'
import ReCaptcha from './ReCaptcha'
import LaravelPagination from './LaravelPagination'
import TableSearch from './TableSearch'
import TermsContent from './TermsContent'
import ContactContent from './ContactContent'
import {HasError, AlertError, AlertSuccess} from 'vform'
import Datepicker from 'vuejs-datepicker';

Vue.component(Icon.name, Icon);
Vue.component(Card.name, Card);
Vue.component(Child.name, Child);
Vue.component(Button.name, Button);
Vue.component(FileUpload.name, FileUpload);
Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);
Vue.component(AlertSuccess.name, AlertSuccess);
Vue.component(TwoFaVerifyForm.name, TwoFaVerifyForm);
Vue.component(ReCaptcha.name, ReCaptcha);
Vue.component(LaravelPagination.name, LaravelPagination);
Vue.component(TableSearch.name, TableSearch);
Vue.component(MoneyInput.name, MoneyInput);
Vue.component(TermsContent.name, TermsContent);
Vue.component(ContactContent.name, ContactContent);
Vue.component('date-picker', Datepicker);
