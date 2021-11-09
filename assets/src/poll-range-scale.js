const ratingScaleContainer = document.querySelector('.poll_rating_scale_container');

if (ratingScaleContainer) {

    const ratingScaleAnswerContainer = document.querySelector(".poll_rating_scale_answer_container");
    //data-start-rating-scale-range
    const ratingScaleStart = ratingScaleAnswerContainer.dataset.startRatingScaleRange;
    //data-end-rating-scale-range
    const ratingScaleEnd = ratingScaleAnswerContainer.dataset.endRatingScaleRange;

    const ratingScaleRange = ratingScaleEnd - ratingScaleStart;
    
    const ratingScaleAnswers = document.querySelectorAll('.poll_rating_scale_item');

    
    const ratingScaleAnswersWidth = Math.floor(100 / ratingScaleAnswers.length) || Math.floor(100 / ratingScaleRange);
    ratingScaleAnswers.forEach(element => {
        element.style.width = ratingScaleAnswersWidth + '%';
    });

}    

