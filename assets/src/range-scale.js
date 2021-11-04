const ratingScaleContainer = document.querySelector('.rating_scale_container');

if (ratingScaleContainer) {

    const ratingScaleAnswerContainer = document.querySelector(".rating_scale_answer_container");
    //data-start-rating-scale-range
    const ratingScaleStart = ratingScaleAnswerContainer.dataset.startRatingScaleRange;
    //data-end-rating-scale-range
    const ratingScaleEnd = ratingScaleAnswerContainer.dataset.endRatingScaleRange;

    console.log("RATING SCALE LENGTH:",ratingScaleEnd - ratingScaleStart)
    const ratingScaleRange = ratingScaleEnd - ratingScaleStart;
    
    const ratingScaleAnswers = document.querySelectorAll('.rating_scale_item');

    
    const ratingScaleAnswersWidth = Math.floor(100 / ratingScaleAnswers.length) || Math.floor(100 / ratingScaleRange);
    console.log(ratingScaleAnswersWidth)
    ratingScaleAnswers.forEach(element => {
        console.log(element)
        element.style.width = ratingScaleAnswersWidth + '%';
    });

}    

