export default function Footer() {
    return (
        <div className="flex flex-col sm:flex-row justify-center mt-4 items-center sm:justify-between">
            <div className="text-center text-sm text-white sm:text-left">
                <div className="flex items-center mb-2.5 sm:mb-0">
                    <svg
                        fill="white"
                        viewBox="0 0 32 32"
                        className="w-5 h-5">
                        <path
                            d="M16.003,0C7.17,0,0.008,7.162,0.008,15.997  c0,7.067,4.582,13.063,10.94,15.179c0.8,0.146,1.052-0.328,1.052-0.752c0-0.38,0.008-1.442,0-2.777  c-4.449,0.967-5.371-2.107-5.371-2.107c-0.727-1.848-1.775-2.34-1.775-2.34c-1.452-0.992,0.109-0.973,0.109-0.973  c1.605,0.113,2.451,1.649,2.451,1.649c1.427,2.443,3.743,1.737,4.654,1.329c0.146-1.034,0.56-1.739,1.017-2.139  c-3.552-0.404-7.286-1.776-7.286-7.906c0-1.747,0.623-3.174,1.646-4.292C7.28,10.464,6.73,8.837,7.602,6.634  c0,0,1.343-0.43,4.398,1.641c1.276-0.355,2.645-0.532,4.005-0.538c1.359,0.006,2.727,0.183,4.005,0.538  c3.055-2.07,4.396-1.641,4.396-1.641c0.872,2.203,0.323,3.83,0.159,4.234c1.023,1.118,1.644,2.545,1.644,4.292  c0,6.146-3.74,7.498-7.304,7.893C19.479,23.548,20,24.508,20,26c0,2,0,3.902,0,4.428c0,0.428,0.258,0.901,1.07,0.746  C27.422,29.055,32,23.062,32,15.997C32,7.162,24.838,0,16.003,0z"/>
                    </svg>

                    <a
                        href="https://github.com/foxviewr/email-orders"
                        target="_blank"
                        rel="noreferrer"
                        className="ml-1 underline">
                        GitHub
                    </a>
                </div>
            </div>

            <div className="ml-4 text-center text-sm text-white sm:text-right sm:ml-0">
                <div className="flex items-center">
                    Docker + MySQL +Laravel + Next.js +
                    <svg
                        fill="white"
                        viewBox="0 0 24 24"
                        className="mx-1 w-5 h-5">
                        <path
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    love
                </div>
            </div>
        </div>
    )
}
