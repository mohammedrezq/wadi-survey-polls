import './styles/poll-main.scss';
import './pollCSV';

const pollSingle = () => {

    const pollSinglePage = document.querySelector(".admin_page_single_poll");

    console.log(pollSinglePage);

    const wadiTable = jQuery(".wadi_poll_table");

    console.log(wadiTable);
    
    if (pollSinglePage) {
            jQuery('.wadi_poll_table').DataTable({
                order: [ [ 1, 'asc' ] ]
            });
    }

}
pollSingle();