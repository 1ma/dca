using DCA.Lib.Contracts;

namespace DCA.Lib.Bitstamp;

public class Buyer : IBuyer<Euro>, IBuyer<Dollar>
{
    Euro IBuyer<Euro>.Buy(Bitcoin amount)
    {
        return new Euro();
    }

    Dollar IBuyer<Dollar>.Buy(Bitcoin amount)
    {
        return new Dollar();
    }
}
