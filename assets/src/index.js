import './styles/main.scss';

const surveySingle = () => {

    const surveySinglePage = document.querySelector(".admin_page_single_survey");

    if (surveySinglePage) {
            jQuery('#single_survey_table').DataTable({
                order: [ [ 1, 'asc' ] ]
            });
    }


}
surveySingle();


// jQuery(document).ready(function( $ ) {
//     // add a 'Settings' tab via JS
//     const navTabWrapper = $('.nav-tab-wrapper');
//     const currentTabs = $('.nav-tab-wrapper a');
//     let activeTab = '';
//     if(!currentTabs.hasClass('nav-tab-active')) {
//       activeTab = ' nav-tab-active';
//     }
//     navTabWrapper.prepend('<a href="#" class="nav-tab fs-tab svg-flags-lite home' + activeTab + '">Settings</a>');
//   });