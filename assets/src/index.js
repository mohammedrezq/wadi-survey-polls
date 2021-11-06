import './styles/main.scss';

const surveySingle = () => {

    const surveySinglePage = document.querySelector(".wadi-survey_page_single_survey");

    if (surveySinglePage) {
            jQuery('#single_survey_table').DataTable({
                order: [ [ 1, 'asc' ] ]
            });
    }


}
surveySingle();
